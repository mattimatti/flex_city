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
