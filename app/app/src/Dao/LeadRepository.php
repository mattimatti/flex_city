<?php
namespace App\Dao;

use RedBeanPHP\R;
use App\Debug;

class LeadRepository extends AbstractRepository
{
    
    /*
     * (non-PHPdoc) @see \App\Dao\AbstractRepository::getType()
     */
    public function getType()
    {
        return Lead::NAME;
    }

    /**
     *
     * @param int $event_id            
     */
    public function findByEvent($event_id = null, $offset = null, $limit = null)
    {
        $params = array();
        $sql = '';
        
        if ($event_id) {
            $params[':event_id'] = $event_id;
            $sql = "event_id = :event_id";
        }
        
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        
        if ($offset) {
            $sql .= " OFFSET $offset ";
        }
        
        return R::findAll($this->getType(), $sql, $params);
    }

    /**
     *
     * @param int $event_id            
     */
    public function countByEvent($event_id = null)
    {
        $params = array();
        $sql = '';
        
        if ($event_id) {
            $params[':event_id'] = $event_id;
            $sql = "event_id = :event_id";
        }
        
        return R::count($this->getType(), $sql, $params);
    }
}