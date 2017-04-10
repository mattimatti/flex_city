<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Debug;
use App\Dao\Event;
use App\Service\LeadService;

class HomeAction extends AbstractAction
{

    /**
     *
     * @var LeadService
     */
    protected $leadService;
    
    /*
     * (non-PHPdoc) @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $event_id = $this->session->get('event_id', Event::ID_WEB);
        $this->setViewData("event_id", $event_id);
        
        $domain = $request->getUri()->getHost();
        
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
