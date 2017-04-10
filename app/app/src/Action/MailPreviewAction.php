<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Debug;
use App\Service\MailRenderer;

final class MailPreviewAction extends AbstractAction
{
    
    /*
     * (non-PHPdoc) @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        
        /* @var $renderer MailRenderer */
        $renderer = $this->container->get('mailRenderer');

        $parameters = array();
        $parameters['email'] = 'mmonti@gmail.com';
        
        $renderer->getMessage($parameters);
        
//         exit('<pre>' . $renderer->getBodyText());
        exit($renderer->getBodyHtml());
    }
}
