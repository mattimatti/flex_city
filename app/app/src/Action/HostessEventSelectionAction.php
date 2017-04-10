<?php
namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\Event;
use App\Dao\Location;
use RedBeanPHP\R;
use App\Debug;
use App\Dao\EventRepository;
use App\Dao\LocationRepository;
use RKA\SessionMiddleware;
use App\Dao\UserRepository;

final class HostessEventSelectionAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        $event_id = $this->session->get('event_id', null);
        $location_id = $this->session->get('event_id', null);
        
        $identity = $this->auth->getIdentity();
        $username = $identity["username"];
        
        $userRepo = new UserRepository();
        $eventRepo = new EventRepository();
        $locationRepo = new LocationRepository();
        
        $user = $userRepo->findByUsername($username);
        $user_id = $user->getID();
        
        if ($request->isPost()) {
            
            $event_id = $request->getParam('event_id');
            $location_id = $request->getParam('location_id');
            
            if ($event_id && $location_id) {
                
                $this->session->set('event_id', $event_id);
                $this->session->set('location_id', $location_id);
                
                return $this->__redirect($response, '/hostess/register');
            }
        }
        
        $this->setViewData("username", $username);
        $this->setViewData("user_id", $user_id);
        $this->setViewData("event_id", $event_id);
        $this->setViewData("location_id", $location_id);
        
        $this->setViewData("events", $eventRepo->findByLocationAndUser($location_id, $user_id));
        $this->setViewData("locations", $locationRepo->findByUser($user_id));
        
        $this->__render($response);
        
        return $response;
    }
}