<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\UserRepository;
use App\Dao\EventRepository;
use App\Acl;
use App\Debug;

final class AdminExportAction extends AbstractAction
{

    public function __invoke(Request $request, Response $response, $args)
    {
        
        Debug::dump($args);
        
        return $response;
    }
}
