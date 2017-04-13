<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractAction;
use App\Dao\StoreRepository;
use App\Dao\EventUserRepository;

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
        $this->repo = new EventUserRepository();
        
        if ($request->isPost()) {
            
            $event = $request->getParam('event_id');
            $user = $request->getParam('user_id');
            
            $payload = array();
            $payload['event_id'] = $event;
            $payload['user_id'] = $user;
            
            $this->repo->create($payload);
            
            return $this->__redirect($response, $request->getHeader('HTTP_REFERER'));
        }
        
        return $response;
    }
}
