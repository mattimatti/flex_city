<?php
namespace App\Action\Auth;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\RedbeanAdapter;
use marcelbonnet\Slim\Auth\Authenticator;
use App\Action\AbstractAction;

final class LogoutAction extends AbstractAction
{

    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->session->clearAll();
        $this->session->destroy();

        $this->auth->logout();
        
        return $this->__redirect($response, "/");
    }
}