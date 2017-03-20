<?php
namespace App\Dao;

use RedBeanPHP\R;
use Zend\Feed\PubSubHubbub\Model\AbstractModel;

abstract class AbstractRepository
{

    abstract function getType();

    public function findAll()
    {
        return R::findAll($this->getType());
    }

    public function get($id)
    {
        return R::load($this->getType(), $id);
    }

    public function validateCreate(array $params)
    {}

    public function create(array $params)
    {
        $this->validateCreate($params);
        
        $bean = R::dispense($this->getType());
        $bean->import($params);
        
        R::store($bean);
        
        return $bean;
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