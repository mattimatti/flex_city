<?php
namespace App\Dao;

use RedBeanPHP\R;

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