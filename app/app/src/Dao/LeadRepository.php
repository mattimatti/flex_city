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
     * @return Lead []        
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

    /**
     *
     * @return Lead
     *
     */
    public function create(array $params)
    {
        return parent::create($params);
    }

    /**
     * Remove a lead by email..
     *
     * @return Lead
     *
     */
    public function removeByEmail($email)
    {
        $params = array();
        $params['email'] = $email;
        
        $lead = R::findOne($this->getType(), 'email = :email', $params);
        
        if ($lead) {
            R::trash($lead);
            return true;
        }
        
        return false;
    }
}