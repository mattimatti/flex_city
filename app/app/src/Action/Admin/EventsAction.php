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
use App\Action\AbstractAction;
use App\Dao\UserRepository;
use App\Dao\User;
use App\Dao\Role;
use App\Acl;
use App\Dao\EventUserRepository;

final class EventsAction extends AbstractAction
{

    public function delete(Request $request, Response $response, $args)
    {
        // instance repos
        $eventRepo = new EventRepository();
        // the event id
        $event_id = $request->getQueryParam('id');
        $eventRepo->trash($event_id);
        return $this->__redirect($response, '/admin/events');
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        
        // instance repos
        $eventRepo = new EventRepository();
        $storeRepo = new StoreRepository();
        $locationRepo = new LocationRepository();
        $userRepo = new UserRepository();
        $eventUserRepo = new EventUserRepository();
        
        // the event id
        $event_id = $request->getQueryParam('id');
        
        // id we have a delete action
        if (isset($args['action']) && $args['action'] == 'delete') {
            return $this->delete($request, $response, $args);
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
        $this->setViewData("hostess", $userRepo->findByRole(Acl::HOSTESS));
        
        $this->__render($response);
        
        return $response;
    }
}
