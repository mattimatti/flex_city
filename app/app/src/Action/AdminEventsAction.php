<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\EventRepository;
use App\Dao\StoretRepository;
use App\Dao\LocationRepository;
use App\Dao\StoreRepository;
use App\Debug;

final class AdminEventsAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        $eventRepo = new EventRepository();
        $storeRepo = new StoreRepository();
        $locationRepo = new LocationRepository();
        
        $event_id = $request->getQueryParam('id');
        
        if (isset($args['action']) && $args['action'] == 'delete') {
            $eventRepo->trash($event_id);
            return $this->__redirect($response, '/admin/events');
        }
        
        if ($event_id) {
            $event = $eventRepo->get($event_id);
            $this->setViewData("item", $event);
        }
        
        if ($request->isPost()) {
            
            $event = $eventRepo->create($request->getParams());
            
            return $this->__redirect($response, '/admin/events');
        }
        
        $this->setViewData("events", $eventRepo->findAll());
        $this->setViewData("stores", $storeRepo->findAll());
        $this->setViewData("locations", $locationRepo->findAll());
        
        $this->__render($response);
        
        return $response;
    }
}
