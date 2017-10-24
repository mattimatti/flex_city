<?php
namespace App\Dao;

use RedBeanPHP\R;
use Slim\Container;

abstract class AbstractRepository
{

    /**
     *
     * @var Container
     */
    protected $container;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @param string $c            
     */
    function __construct($container = null)
    {
        $this->container = $container;
        $this->logger = $container->get("logger");
    }

    abstract function getType();

    /**
     *
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function findAll()
    {
        return R::findAll($this->getType());
    }

    /**
     *
     * @param unknown $id            
     * @return \RedBeanPHP\OODBBean
     */
    public function get($id)
    {
        return R::load($this->getType(), $id);
    }

    /**
     *
     * @param string $key            
     * @param any $value            
     * @return boolean
     */
    public function existsByFieldAndValue($key, $value)
    {
        return (R::findOne($this->getType(), "$key = ?", array(
            $value
        )) !== null);
    }

    /**
     *
     * @param array $params            
     * @return Ambigous <multitype:, \RedBeanPHP\OODBBean, \RedBeanPHP\Util\OODBBean, unknown, multitype:Ambigous <multitype:, \RedBeanPHP\Util\OODBBean> >
     */
    public function create(array $params)
    {
        $bean = R::dispense($this->getType());
        $bean->import($params);
        
        R::store($bean);
        
        return $bean->box();
    }

    /**
     *
     * @param array $params            
     */
    public function update(array $params, $bean)
    {
        $bean->import($params);
        
        R::store($bean);
        
        return $bean->box();
    }

    /**
     *
     * @param int $id            
     */
    public function trash($id)
    {
        return R::trash($this->getType(), $id);
    }

    /**
     *
     * @param AbstractModel $bean            
     */
    public function store($bean)
    {
        R::store($bean);
        return $bean;
    }
}