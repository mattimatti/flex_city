<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\EventRepository;
use App\Dao\LocationRepository;
use App\Dao\StoreRepository;
use App\Debug;
use App\Dao\LeadRepository;
use Kilte\Pagination\Pagination;
use App\Action\AbstractAction;
use App\Service\LeadService;

final class LeadsAction extends AbstractAction
{

    /**
     *
     * @var LeadService
     */
    protected $leadService;

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->leadService = $this->container->get('leadService');
        
        $leadRepo = $this->leadService->getLeadRepo();
        
        $action = $request->getParam('action', 'view');
        
        $currentPage = $request->getParam('page', 0);
        $perPage = 50;
        
        $filters = $request->getParam('filters');
        
        if (! $filters) {
            $filters = array();
        }
        
        // if ($request->isPost()) {
        // Debug::dump($filters);
        // }
        
        $totalItems = $leadRepo->countByparams($filters);
        
        $pagination = new Pagination($totalItems, $currentPage, $perPage);
        
        $offset = $pagination->offset();
        $limit = $pagination->limit();
        $pages = $pagination->build();
        
        $leads = $leadRepo->findByParams($filters, $offset, $limit);
        
        if ($action == 'export') {
            $this->leadService->export($leads);
            return;
        }
        
        // Debug::dump($pages);
        
        $this->setViewData("pages", $pages);
        $this->setViewData("leads", $leads);
        $this->setViewData("filters", $filters);
        
        $this->setViewData("distinctprizes", $leadRepo->getDistinct('prize'));
        $this->setViewData("distinctcities", $leadRepo->getDistinct('city'));
        $this->setViewData("distinctgenders", $leadRepo->getDistinct('gender'));
        $this->setViewData("distinctmodels", $leadRepo->getDistinct('model'));
        $this->setViewData("distinctcountries", $leadRepo->getDistinct('country'));
        $this->setViewData("distinctdays", $leadRepo->getDistinct('day'));
        $this->setViewData("distinctmonths", $leadRepo->getDistinct('month'));
        $this->setViewData("distinctlangs", $leadRepo->getDistinct('lang'));
        $this->setViewData("distincthours", $leadRepo->getDistinct('hour'));
        
        $this->__render($response);
        
        return $response;
    }
}
