<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\RedbeanAdapter;
use marcelbonnet\Slim\Auth\Authenticator;
use App\Acl;
use App\Debug;

final class LoginAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        if ($request->isPost()) {
            
            $result = $this->auth->authenticate($request->getParam("username"), $request->getParam("password"));
            
            if ($result->isValid()) {
                $this->logger->info("User logged in..");
                $this->logger->info(print_r($result, true));
                
                if ($result->getIdentity()) {
                    $data = $result->getIdentity();
                    
                    $this->flash->addSuccess('Login Successful');
                    
                    if ($data["role"][0]["role"] == Acl::ADMIN) {
                        return $response->withRedirect("/admin");
                    }
                    
                    if ($data["role"][0]["role"] == Acl::HOSTESS) {
                        return $response->withRedirect("/hostess");
                    }
                }
            } else {
                
                $message = implode('', $result->getMessages());
                $this->logger->error($message);
                
                $this->setViewData("errors", $message);
            }
        } else {
            $this->logger->info("Waiting for login");
        }
        
        $this->__render($response);
        
        return $response;
    }
}