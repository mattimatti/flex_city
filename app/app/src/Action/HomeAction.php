<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Debug;
use App\Dao\Event;

class HomeAction extends AbstractAction
{
    
    /*
     * (non-PHPdoc) @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $domain = $request->getUri()->getHost();
        
        $event_id = $this->session->get('event_id', Event::ID_WEB);
        $this->setViewData("event_id", $event_id);
        
        $template = "index.twig";
        
        if (isset($args['page'])) {
            $template = $args['page'] . ".twig";
        }
        
        $data = array();
        $data["assets"] = "/$domain";
        $data["domain"] = "$domain";
        $data["settings"] = $this->container->get('settings');
        
        $this->setViewData($data);
        
        $this->view->render($response, $template, $this->getViewData());
    }
}
