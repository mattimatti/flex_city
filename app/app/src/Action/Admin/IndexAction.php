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
        
        $leadRepo = $this->leadService->getLeadRepo();
        
        $this->setViewData("distinctprizes", $leadRepo->getDistinct('prize'));
        $this->setViewData("distinctmodels", $leadRepo->getDistinct('model'));
        $this->setViewData("distinctcountries", $leadRepo->getDistinct('country'));
        $this->setViewData("distinctdays", $leadRepo->getDistinct('day'));
        $this->setViewData("distinctmonths", $leadRepo->getDistinct('month'));
        $this->setViewData("distinctlangs", $leadRepo->getDistinct('lang'));
        $this->setViewData("distincthours", $leadRepo->getDistinct('hour'));
        $this->setViewData("since", $this->leadService->getSummary(- 7));
        $this->setViewData("overall", $this->leadService->getSummary());
        
    }
}
