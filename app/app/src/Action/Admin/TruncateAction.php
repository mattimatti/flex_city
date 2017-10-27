<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractAction;
use App\Service\LeadService;

final class TruncateAction extends AbstractAction
{

    /**
     *
     * @var LeadService
     */
    protected $leadService;
    
    /*
     * (non-PHPdoc) @see \App\Action\AbstractAction::execute()
     */
    public function execute()
    {
        $this->leadService = $this->container->get('leadService');
        $this->leadService->truncate();
        
    }
}
