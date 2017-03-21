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
use App\Dao\LeadRepository;
use App\Service\LeadService;

final class HomeRegisterAction extends LeadRegisterAction
{
    
    /*
     * (non-PHPdoc) @see \App\Action\AbstractAction::getTemplate()
     */
    public function getTemplate()
    {
        return 'index.twig';
    }
}