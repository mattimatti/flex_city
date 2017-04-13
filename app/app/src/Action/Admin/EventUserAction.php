<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractAction;
use App\Dao\StoreRepository;
use App\Dao\EventUserRepository;
use App\Dao\EventRepository;
use App\Dao\UserRepository;

final class EventUserAction extends AbstractAction
{

    /**
     *
     * @var EventUserRepository
     */
    protected $repo;

    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->repo = new EventRepository();
        $userRepo = new UserRepository();
        
        if ($request->isPost()) {
            
            $event_id = $request->getParam('event_id');
            
            $event = $this->repo->get($event_id);
            
            $user_id = $request->getParam('user_id');
            
            $user = $userRepo->get($user_id);
            
            $event->sharedUserList[] = $user;
            
            $this->repo->store($event);
            
            return $this->__redirect($response, $request->getHeader('HTTP_REFERER'));
        }
        
        return $response;
    }
}
