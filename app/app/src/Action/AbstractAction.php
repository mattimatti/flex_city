<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use marcelbonnet\Slim\Auth\Authenticator;
use Slim\Container;
use RKA\SessionMiddleware;
use RKA\Session;
use Slim\Flash\Messages;

abstract class AbstractAction
{

    /**
     *
     * @var Twig
     */
    protected $view;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var Authenticator
     */
    protected $auth;

    /**
     *
     * @var array
     */
    protected $viewData;

    /**
     *
     * @var Container
     */
    protected $container;

    /**
     *
     * @var Session
     */
    protected $session;

    /**
     *
     * @var \App\Messages
     */
    protected $flash;

    /**
     *
     * @param Twig $view            
     * @param LoggerInterface $logger            
     * @param string $auth            
     */
    function __construct(Container $container)
    {
        $this->container = $container;
        
        $this->view = $container->get("view");
        $this->logger = $container->get("logger");
        
        $this->auth = $container->get("authenticator");
        
        $this->session = $container->get("session");
        $this->flash = $container->get("flash");
        
        $this->viewData = array();
    }

    /**
     *
     * @return array:
     */
    public function getViewData()
    {
        return $this->viewData;
    }

    /**
     *
     * @param string|array: $viewData            
     * @param any: $value            
     */
    public function setViewData($viewData, $value = null)
    {
        if (! empty($value) && is_string($viewData)) {
            $viewData = array(
                "$viewData" => $value
            );
        }
        
        if (! is_array($viewData)) {
            $viewData = array();
        }
        
        if (! empty($this->viewData)) {
            $this->viewData = array_merge($this->viewData, $viewData);
        } else {
            $this->viewData = $viewData;
        }
    }

    /**
     *
     * @return string
     */
    public function getTemplate()
    {
        $arr = explode('\\', get_class($this));
        $arr = array_reverse($arr);
        $folder = str_replace("action", "", strtolower($arr[1]));
        $tpl = str_replace("action", "", strtolower($arr[0])) . ".twig";
        
        if (! empty($folder)) {
            return $folder . '/' . $tpl;
        }
        
        return $tpl;
    }

    public function execute()
    {}

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");
        
        $this->execute();
        
        $this->__render($response);
        
        return $response;
    }

    /**
     *
     * @param Response $response            
     */
    public function __render(Response $response)
    {
        $this->logger->info(print_r($this->getViewData(), 1));
        $this->view->render($response, $this->getTemplate(), $this->getViewData());
    }

    public function __redirect(Response $response, $url)
    {
        return $response->withStatus(302)->withHeader('Location', $url); // ->withRedirect($url);
    }
}
