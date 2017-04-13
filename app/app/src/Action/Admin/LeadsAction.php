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

final class LeadsAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        $eventRepo = new EventRepository();
        $storeRepo = new StoreRepository();
        $locationRepo = new LocationRepository();
        $leadRepo = new LeadRepository();
        
        $event_id = $request->getQueryParam('event_id', null);
        $this->setViewData("event_id", $event_id);
        
        if ($event_id) {
            $this->setViewData("event", $eventRepo->get($event_id));
        }
        
        $currentPage = $request->getQueryParam('page', 0);
        $perPage = 50;
        
        $totalItems = $leadRepo->countByEvent($event_id);
        
        $pagination = new Pagination($totalItems, $currentPage, $perPage);
        
        $offset = $pagination->offset();
        $limit = $pagination->limit();
        $pages = $pagination->build();
        
        $leads = $leadRepo->findByEvent($event_id, $offset, $limit);
        
        // Debug::dump($pages);
        
        $this->setViewData("pages", $pages);
        $this->setViewData("events", $eventRepo->findAll());
        $this->setViewData("leads", $leads);
        
        $this->__render($response);
        
        return $response;
    }
}
