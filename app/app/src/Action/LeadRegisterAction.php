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
        
        $event_id = $this->getEventId();
        $this->setViewData("event_id", $event_id);
        
        if ($request->isPost()) {
            
            $redirect = $request->getParam('redirect');
            
            try {
                
                if (false === $request->getAttribute('csrf_status')) {
                    throw new \Exception($this->translator->trans('CSFR_EXCEPTION'));
                }
                
                $lead = $this->leadService->create($request->getParams());
                
                $this->flash->addSuccess($this->translator->trans('LEAD_REGISTER_SUCCESS'));
                
                return $response->withStatus(302)->withHeader('Location', $redirect);
            } catch (\InvalidArgumentException $ex) {
                
                // Handle an invalid argument
                
                $this->setViewData("item", $request->getParams());
                $this->flash->addError($ex->getMessage());
                
                $this->setViewData("errors", $ex->getMessage());
                $this->setViewData($request->getParams());
                
                //
            } catch (\Exception $ex) {
                
                // redirect a fatal exception like csrf
                
                $this->flash->addError($ex->getMessage());
                
                return $response->withStatus(302)->withHeader('Location', $redirect);
            }
        }
        
        $eventRepo = new EventRepository();
        
        $event = $eventRepo->get($event_id);
        $this->setViewData("event", $event);
        
        $this->__render($response);
        
        return $response;
    }

    /**
     *
     * @return int
     */
    public function getEventId()
    {
        return $this->session->get('event_id');
    }
}