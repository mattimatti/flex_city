<?php
namespace App\Action\Admin;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractAction;
use App\Dao\StoreRepository;

final class StoresAction extends AbstractAction
{

    /**
     *
     * @var StoreRepository
     */
    protected $storeRepo;

    /**
     *
     * @param Request $request            
     * @param Response $response            
     * @param unknown $args            
     */
    public function delete(Request $request, Response $response, $args)
    {
        // instance repos
        $repo = new StoreRepository();
        // the id
        $id = $request->getQueryParam('id');
        $repo->trash($id);
        return $this->__redirect($response, '/admin/stores');
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Action\AbstractAction::__invoke()
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->storeRepo = new StoreRepository();
        
        if (isset($args['id'])) {
            $id = $args['id'];
            $store = $this->storeRepo->get($id);
            $this->setViewData("item", $store);
        }
        
        // id we have a delete action
        if (isset($args['action']) && $args['action'] == 'delete') {
            return $this->delete($request, $response, $args);
        }
        
        if ($request->isPost()) {
            
            $payload = array();
            $payload['name'] = $request->getParam('name');
            
            if (! $store) {
                $store = $this->storeRepo->create($payload);
            } else {
                $store = $this->storeRepo->update($payload, $store);
            }
            
            return $this->__redirect($response, '/admin/stores');
        }
        
        $this->setViewData("stores", $this->storeRepo->findAll());
        
        $this->__render($response);
        
        return $response;
    }
}
