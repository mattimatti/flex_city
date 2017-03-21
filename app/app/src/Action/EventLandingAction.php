<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\RedbeanAdapter;
use marcelbonnet\Slim\Auth\Authenticator;
use App\Debug;
use App\Dao\EventRepository;

final class EventLandingAction extends AbstractAction
{

    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $eventRepo = new EventRepository();
        exit("Display the landing");
        Debug::dump($args);
    }
}