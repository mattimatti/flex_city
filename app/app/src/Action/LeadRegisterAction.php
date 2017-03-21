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

class LeadRegisterAction extends AbstractAction
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
        
        $event_id = $this->session->get('event_id', Event::ID_WEB);
        $this->setViewData("event_id", $event_id);
        
        if ($request->isPost()) {
            
            try {
                if (false === $request->getAttribute('csrf_status')) {
                    throw new \Exception("Invalid check");
                }
                $lead = $this->leadService->create($request->getParams());
                
                $this->flash->addSuccess("Lead Registered Successfully");
                
                // return $this->__redirect($response, $request->getUri());
                $url = $request->getUri();
                
                return $response->withStatus(302)->withHeader('Location', $url);
            } catch (\Exception $ex) {
                
                $this->setViewData("item", $request->getParams());
                $this->flash->addError($ex->getMessage());
                
                $this->setViewData("errors", $ex->getMessage());
                $this->setViewData($request->getParams());
            }
        }
        
        $eventRepo = new EventRepository();
        
        
        
        $event = $eventRepo->get($event_id);
        $this->setViewData("event", $event);
        
        $this->__render($response);
        
        return $response;
    }
}