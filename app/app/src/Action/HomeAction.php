<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Debug;

final class HomeAction extends AbstractAction
{
    
    /*
     * (non-PHPdoc) @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        
        $domain = $request->getUri()->getHost();
        
        $template = "index.twig";
        $data = array();
        $data["assets"] = "/$domain";
        $data["domain"] = "$domain";
        $data["settings"] = $this->container->get('settings');
        
        $this->view->render($response, $template, $data);
    }
}
