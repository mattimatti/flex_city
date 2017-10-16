<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractAction;
use App\Service\LeadService;

final class IndexAction extends AbstractAction
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
        
        
        $viewData = array();
        
        $viewData['distinctprizes'] = $this->leadService->getLeadRepo()->getDistinct('prize');
        $viewData['distinctmodels'] = $this->leadService->getLeadRepo()->getDistinct('model');
        $viewData['distinctcountries'] = $this->leadService->getLeadRepo()->getDistinct('country');
        $viewData['since'] = $this->leadService->getSummary(-7);
        
        $viewData['overall'] = $this->leadService->getSummary();
        
       // print_r($viewData);
       // exit();
        
        $this->setViewData($viewData);
    }
}
