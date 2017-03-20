<?php
namespace App\Dao;

use RedBeanPHP\R;
use App\Debug;

class EventRepository extends AbstractRepository
{
    
    /*
     * (non-PHPdoc) @see \App\Dao\AbstractRepository::getType()
     */
    public function getType()
    {
        return Event::NAME;
    }

    public function findBypermalink($permalink)
    {
        $params = array();
        $params[':permalink'] = $permalink;
        
        return R::findOne($this->getType(), "permalink = :permalink", $params);
    }

    /**
     *
     * @param int $location_id            
     * @param int $user_id            
     * @return Event []
     */
    public function findByLocationAndUser($location_id, $user_id)
    {
        $params = array();
        $params[':location_id'] = $location_id;
        $params[':user_id'] = $user_id;
        
        $sqla = array();
        $sqla[] = 'SELECT';
        $sqla[] = 'e.*';
        $sqla[] = 'FROM';
        $sqla[] = 'event_user eu';
        $sqla[] = 'INNER JOIN user u ON u.id = eu.user_id';
        $sqla[] = 'INNER JOIN event e ON e.id = eu.event_id';
        $sqla[] = 'WHERE';
        $sqla[] = 'u.id = :user_id';
        $sqla[] = 'AND';
        $sqla[] = 'e.location_id = :location_id';
        
        $sql = implode(' ', $sqla);
        
        // Debug::dump($sql);
        
        $rows = R::getAll($sql, $params);
        
        $events = R::convertToBeans($this->getType(), $rows);
        
        return $events;
    }
}