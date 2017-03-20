<?php
namespace App\Dao;

use RedBeanPHP\R;
use App\Debug;

class RoleRepository extends AbstractRepository
{
    
    /*
     * (non-PHPdoc) @see \App\Dao\AbstractRepository::getType()
     */
    public function getType()
    {
        return Role::NAME;
    }

    /**
     *
     * @param unknown $role            
     * @return Ambigous <\RedBeanPHP\OODBBean, NULL>
     */
    public function findByAclString($role)
    {
        $params = array();
        $params[':role'] = $role;
        
        return R::findOne($this->getType(), "role = :role ", $params);
    }
}