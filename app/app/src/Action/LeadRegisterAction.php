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
        
        if ($request->isPost()) {
            
            
            $this->logger->info(print_r($request, 1));
            
            try {
                
                
                $lead = $this->leadService->create($request->getParams());
                
                echo ("ok");
                exit();
                
                //
            } catch (\InvalidArgumentException $ex) {
                
                echo ("invalid params: " . $ex->getMessage());
                exit();
                
                //
            } catch (\Exception $ex) {
                
                // redirect a fatal exception like csrf or user already registerd
                echo ($ex->getMessage());
                exit();
            }
        }
        
        return $response;
    }
}