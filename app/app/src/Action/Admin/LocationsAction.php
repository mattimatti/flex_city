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

    public function delete(Request $request, Response $response, $args)
    {
        // instance repos
        $repo = new LocationRepository();
        // the id
        $id = $request->getQueryParam('id');
        $repo->trash($id);
        return $this->__redirect($response, '/admin/locations');
    }

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
            $location = $this->locationRepo->get($id);
            $this->setViewData("item", $location);
        }
        
        // id we have a delete action
        if (isset($args['action']) && $args['action'] == 'delete') {
            return $this->delete($request, $response, $args);
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
