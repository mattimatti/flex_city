<?php
namespace App\Dao;

use RedBeanPHP\R;
use App\Acl;

class LocationRepository extends AbstractRepository
{
    
    /*
     * (non-PHPdoc) @see \App\Dao\AbstractRepository::getType()
     */
    public function getType()
    {
        return Location::NAME;
    }

    /**
     *
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function findAllButWeb()
    {
        return R::findAll($this->getType(), 'id != 1');
    }

    /**
     *
     * @param int $user_id            
     * @return Ambigous <multitype:, multitype:NULL >
     */
    public function findByUser($user_id)
    {
        $params = array();
        $params[':user_id'] = $user_id;
        
        $sqla = array();
        $sqla[] = 'SELECT';
        $sqla[] = 'l.*';
        $sqla[] = 'FROM';
        $sqla[] = 'event_user eu';
        $sqla[] = 'INNER JOIN user u ON u.id = eu.user_id';
        $sqla[] = 'INNER JOIN event e ON e.id = eu.event_id';
        $sqla[] = 'INNER JOIN location l ON l.id = e.location_id';
        $sqla[] = 'WHERE';
        $sqla[] = 'u.id = :user_id';
        
        $sql = implode(' ', $sqla);
        
        $rows = R::getAll($sql, $params);
        $locations = R::convertToBeans($this->getType(), $rows);
        return $locations;
    }
}