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

final class HostessLeadRegisterAction extends AbstractAction
{

    /**
     *
     * @var LeadService
     */
    protected $leadService;

    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->leadService = $this->container->get('leadService');
        
        $event_id = $this->session->get('event_id', null);
        
        $eventRepo = new EventRepository();
        
        $event = $eventRepo->get($event_id);
        
        if ($request->isPost()) {
            
            
            try {
                
                $lead = $this->leadService->create($request->getParams());
                
                
                //Debug::dump($lead);
                
                //return $this->__redirect($response, '/hostess/register');
            } catch (\Exception $ex) {
                $this->setViewData("errors", $ex->getMessage());
                $this->setViewData($request->getParams());
            }
        }
        
        $this->setViewData("event_id", $event_id);
        
        $this->setViewData("event", $event);
        
        $this->__render($response);
        
        return $response;
    }
}