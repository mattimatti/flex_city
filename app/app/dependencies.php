<?php
use marcelbonnet\Slim\Auth\Route\AuthorizableRouter;
use marcelbonnet\Slim\Auth\Adapter\LdapRdbmsAdapter;
use marcelbonnet\Slim\Auth\ServiceProvider\SlimAuthProvider;
use marcelbonnet\Slim\Auth\Handlers\RedirectHandler;
use marcelbonnet\Slim\Auth\Middleware\Authorization;

use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

use App\Acl;
use App\RedbeanAdapter;
use RedBeanPHP\R;
use App\Dao\Role;
use App\Dao\User;
use App\Fixtures;
use RKA\SessionMiddleware;
use RKA\Session;
use App\Service\LeadService;
use App\Dao\LeadRepository;
use App\Service\UserService;
use App\Dao\UserRepository;
use App\Dao\RoleRepository;
use App\Service\MailService;
use App\Service\MailRenderer;
use App\Debug;
use App\Dao\Sender;
use App\CsrfExtension;
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// cross forgery protection

$container['csrf'] = function ($c)
{
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next)
    {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};

// Register provider
$container['flash'] = function ($c)
{
    return new \App\Messages();
};

// Translator
$container['translator'] = function ($c)
{
    $settings = $c->get('settings');
    
    $defaultlang = $settings['lang'];
    
    // First param is the "default language" to use.
    $translator = new Translator($defaultlang, new MessageSelector());
    
    // Set a fallback language incase you don't have a translation in the default language
    $translator->setFallbackLocales([
        'en_US'
    ]);
    
    // Add a loader that will get the php files we are going to store our translations in
    $translator->addLoader('php', new PhpFileLoader());
    
    // Add language files here
    $translator->addResource('php', __DIR__ . '/../lang/it_IT.php', 'it_IT'); // Italian
    $translator->addResource('php', __DIR__ . '/../lang/de_DE.php', 'de_DE'); // German
    $translator->addResource('php', __DIR__ . '/../lang/en_US.php', 'en_US'); // English US
    $translator->addResource('php', __DIR__ . '/../lang/en_EN.php', 'en_EN'); // English
    $translator->addResource('php', __DIR__ . '/../lang/fr_FR.php', 'fr_FR'); // French
    $translator->addResource('php', __DIR__ . '/../lang/es_ES.php', 'es_ES'); // Spanish
    $translator->addResource('php', __DIR__ . '/../lang/nl_NL.php', 'nl_NL'); // Spanish
    
    $translator->addResource('php', __DIR__ . '/../lang/it_IT.php', 'it_ch'); // Italian Sweitz
    $translator->addResource('php', __DIR__ . '/../lang/fr_FR.php', 'fr_ch'); // French
    $translator->addResource('php', __DIR__ . '/../lang/de_DE.php', 'de_ch'); // Italian
    
    return $translator;
};

// Twig
$container['view'] = function ($c)
{
    $settings = $c->get('settings');
    
    $custompath = __DIR__ . '/../web/domains/' . CURRENT_DOMAIN . '/templates';
    // exit($custompath);
    
    if (file_exists($custompath)) {
        $paths = array(
            $custompath,
            $settings['view']['template_path']
        );
    } else {
        $paths = array(
            $settings['view']['template_path']
        );
    }
    
    $view = new Slim\Views\Twig($paths, $settings['view']['twig']);
    
    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')
        ->getUri()));
    
    $view->addExtension(new TranslationExtension($c->get('translator')));
    
    $view->addExtension(new Twig_Extension_Debug());
    
    $csrf = $c->get('csrf');
    $view->addExtension(new CsrfExtension($csrf));
    
    $view->addExtension(new Knlv\Slim\Views\TwigMessages($c->get('flash')));
    
    return $view;
};

// -----------------------------------------------------------------------------
// Database
// -----------------------------------------------------------------------------
define('REDBEAN_MODEL_PREFIX', '\\App\\Dao\\');

$container['database'] = function ($c)
{
    $dbsettings = $c['settings']['database'];
    R::setup('mysql:host=' . $dbsettings['host'] . ';dbname=' . $dbsettings['dbname'], $dbsettings['user'], $dbsettings['password']);
    R::freeze(TRUE);
};

$app->getContainer()->get('database');

// -----------------------------------------------------------------------------
// Authentication/Authorization
// -----------------------------------------------------------------------------

$acl = new Acl();

// ACLed Slim Route
$container['router'] = new AuthorizableRouter(null, $acl);
$container['acl'] = $acl;

$container["authAdapter"] = new RedbeanAdapter();

$slimAuthProvider = new SlimAuthProvider();
$slimAuthProvider->register($container);

$redirectHandler = new RedirectHandler("/auth/login", "/auth/login");

$auth = new Authorization($container["auth"], $container['acl'], $redirectHandler);
$app->add($auth);

// checks:
// username=(is_array(@$c["auth"]->getStorage()->read()))? @$c["auth"]->getStorage()->read()["username"] : @$c["auth"]->getStorage()->read();
// userRoles=(is_array(@$c["auth"]->getStorage()->read()))? @$c["auth"]->getStorage()->read()["role"] : array();

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c)
{
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// session middleware
$container['sessionMiddleware'] = function ($c)
{
    $sessionMiddleware = new SessionMiddleware([
        'name' => 'MySessionName'
    ]);
    $sessionMiddleware->start();
    
    return $sessionMiddleware;
};

// session
$container['session'] = function ($c)
{
    return new Session();
};

// leadservice
$container['leadService'] = function ($c)
{
    // compose the service params
    
    $leadRepo = new LeadRepository();
    $mailService = $c->get('mailService');
    $translator = $c->get('translator');
    $settings = $c->get('settings');
    
    $leadService = new LeadService($leadRepo, $mailService, $translator, $settings);
    
    return $leadService;
};

// userService
$container['userService'] = function ($c)
{
    $userRepo = new UserRepository();
    $roleRepo = new RoleRepository();
    return new UserService($userRepo, $roleRepo);
};

// mailRenderer
$container['mailRenderer'] = function ($c)
{
    $twigEnv = $c->get('view')->getEnvironment();
    $settings = $c->get('settings');
    $renderer = new MailRenderer($twigEnv, $settings);
    return $renderer;
};

// mailService
$container['mailService'] = function ($c)
{
    
    $settings = $c->get('settings');
    
    $throwExceptions = true;
    $mailer = new \PHPMailer($throwExceptions);
    
    $mailer->Host = "mail.yourdomain.com"; // SMTP server
                                           
    // $mailer->SMTPDebug = 2; // enables SMTP debug information (for testing)
    
    $mailer->SMTPAuth = true; // enable SMTP authentication
    $mailer->SMTPSecure = "tls"; // sets the prefix to the servier
    $mailer->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
    $mailer->Port = 587; // set the SMTP port for the GMAIL server
    $mailer->Username = "noreply.flexinthecity@gmail.com"; // GMAIL username
    $mailer->Password = "Flex7102"; // GMAIL password
    $mailer->isSMTP();
    
    
    // Set who the message is to be sent from
    $mailer->setFrom('noreply.flexinthecity@gmail.com', 'Flex in the City');
    
    // use sendmail?? NO IF GMAIL
    // $mailer->IsSendmail();
    
    $renderer = $c->get('mailRenderer');
    $service = new MailService($mailer, $renderer);
    
    // configure the sender
    $config = $settings['mailer'];
    $sender = new Sender($config);
    
    $service->addSender($sender);
    
    return $service;
};

