<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\UserRepository;
use App\Dao\EventRepository;
use App\Acl;
use App\Service\UserService;
use App\Debug;
use App\Action\AbstractAction;

final class HostessAction extends AbstractAction
{

    /**
     *
     * @var UserService
     */
    protected $userService;

    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->userService = $this->container->get('userService');
        
        if (isset($args['id'])) {
            $id = $args['id'];
            $user = $this->userService->getRepo()->get($id);
            $this->setViewData("item", $user);
        }
        
        $userRepo = new UserRepository();
        
        if ($request->isPost()) {
            
            $username = $request->getParam('username');
            $password = $request->getParam('password');
            
            $name = $request->getParam('name');
            $surname = $request->getParam('surname');
            
            $payload = array();
            $payload['username'] = $username;
            $payload['name'] = $name;
            $payload['surname'] = $surname;
            $payload['password'] = $password;
            
            if (! $user) {
                $user = $this->userService->create($payload, $password, Acl::HOSTESS);
            } else {
                $user = $this->userService->update($payload, $user);
                
            }
            
            return $this->__redirect($response, '/admin/hostess');
        }
        
        $this->setViewData("hostess", $userRepo->findByRole(Acl::HOSTESS));
        
        
        $this->__render($response);
        
        return $response;
    }
}
