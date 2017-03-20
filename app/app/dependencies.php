<?php
use marcelbonnet\Slim\Auth\Route\AuthorizableRouter;
use marcelbonnet\Slim\Auth\Adapter\LdapRdbmsAdapter;
use marcelbonnet\Slim\Auth\ServiceProvider\SlimAuthProvider;
use marcelbonnet\Slim\Auth\Handlers\RedirectHandler;
use marcelbonnet\Slim\Auth\Middleware\Authorization;
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
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c)
{
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);
    
    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')
        ->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    
    return $view;
};

// Flash messages
$container['flash'] = function ($c)
{
    return new Slim\Flash\Messages();
};

// $link = mysql_connect('127.0.0.1', 'root', '');
// mysql_select_db('symfony', $link);
// mysql_set_charset('UTF-8', $link);
define('REDBEAN_MODEL_PREFIX', '\\App\\Dao\\');

R::setup('mysql:host=localhost;dbname=symfony', 'root', '');

$fixtures = new Fixtures();

// $fixtures->load();
// $fixtures->dump();

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
    
    // compose the service..
    $leadRepo = new LeadRepository();
    
    return new LeadService($leadRepo);
};

// userService
$container['userService'] = function ($c)
{
    $userRepo = new UserRepository();
    $roleRepo = new RoleRepository();
    return new UserService($userRepo, $roleRepo);
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container["App\Action\HomeAction"] = function ($c)
{
    return new App\Action\HomeAction($c);
};

$container["App\Action\AdminAction"] = function ($c)
{
    return new App\Action\AdminAction($c);
};

$container["App\Action\LoginAction"] = function ($c)
{
    return new App\Action\LoginAction($c);
};

$container["App\Action\LogoutAction"] = function ($c)
{
    return new App\Action\LogoutAction($c);
};

$container["App\Action\HostessEventSelectionAction"] = function ($c)
{
    return new App\Action\HostessEventSelectionAction($c);
};

$container["App\Action\HostessLoginAction"] = function ($c)
{
    return new App\Action\HostessLoginAction($c);
};
