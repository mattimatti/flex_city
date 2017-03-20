<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\UserRepository;
use App\Dao\EventRepository;
use App\Acl;
use App\Service\UserService;
use App\Debug;

final class AdminHostessAction extends AbstractAction
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
        
        $userRepo = new UserRepository();
        
        if ($request->isPost()) {
            
            $username = $request->getParam('username');
            $password = $request->getParam('password');
            
            $user = $this->userService->create(array(
                'username' => $username
            ), $password, Acl::HOSTESS);
            
            
            return $this->__redirect($response, '/admin/hostess');
        }
        
        $this->setViewData("hostess", $userRepo->findByRole(Acl::HOSTESS));
        
        $this->__render($response);
        
        return $response;
    }
}
