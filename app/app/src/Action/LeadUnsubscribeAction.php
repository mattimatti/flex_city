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

class LeadUnsubscribeAction extends AbstractAction
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
        
        $email = null;
        if (isset($args['email'])) {
            $email = $args['email'];
            $this->leadService->remove($email);
        }
        
        $this->__render($response);
    }
    
    
    /*
     * (non-PHPdoc) @see \App\Action\AbstractAction::getTemplate()
    */
    public function getTemplate()
    {
        return 'unsubscribe.twig';
    }
    
}