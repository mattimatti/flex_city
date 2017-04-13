<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractAction;
use App\Dao\LocationRepository;

final class LocationsAction extends AbstractAction
{
    
    /**
     *
     * @var LocationRepository
     */
    protected $locationRepo;
    
    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->locationRepo = new LocationRepository();
    
        if (isset($args['id'])) {
            $id = $args['id'];
            $user = $this->locationRepo->get($id);
            $this->setViewData("item", $user);
        }
    
        if ($request->isPost()) {
    
            $payload = array();
            $payload['name'] = $request->getParam('name');
    
            if (! $location) {
                $location = $this->locationRepo->create($payload);
            } else {
                $location = $this->locationRepo->update($payload, $location);
            }
    
            return $this->__redirect($response, '/admin/locations');
        }
    
        $this->setViewData("locations", $this->locationRepo->findAll());
    
        $this->__render($response);
    
        return $response;
    }
}
