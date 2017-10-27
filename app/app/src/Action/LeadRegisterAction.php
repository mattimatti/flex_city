<?php
namespace App\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Dao\Event;
use App\Dao\Location;
use RedBeanPHP\R;
use App\Debug;
use RKA\SessionMiddleware;
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
        
        $this->logger->debug("entra lead register");
        
        if ($request->isPost()) {
            
            $this->logger->debug(print_r($request->getParams(), 1));
            
            try {
                
                $lead = $this->leadService->create($request->getParams());
                
                $this->logger->info("ok saved");
                
                echo ("ok");
                exit();
                
                //
            } catch (\InvalidArgumentException $ex) {
                
                $this->logger->error("invalid params: " . $ex->getMessage());
                echo ("invalid params: " . $ex->getMessage());
                exit();
                
                //
            } catch (\Exception $ex) {
                
                $this->logger->error("error: " . $ex->getMessage());
                // redirect a fatal exception like csrf or user already registerd
                echo ($ex->getMessage());
                exit();
            }
        }
        
        return $response;
    }
}