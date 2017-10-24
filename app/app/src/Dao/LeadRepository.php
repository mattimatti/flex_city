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

    private function strLreplace($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);
        
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }
        
        return $subject;
    }

    /**
     *
     * @param int $event_id            
     * @return Lead []
     */
    public function findByParams($params = array(), $offset = null, $limit = null)
    {
        $bindings = array();
        $sql = '1=1';
        
        // Debug::dump($params);
        
        foreach ($params as $key => $obj) {
            
            $value = null;
            if (isset($obj['value'])) {
                $value = $obj['value'];
            }
            
            if (isset($obj['type'])) {
                $type = $obj['type'];
            }
            
            if (! empty($value)) {
                
                $sql .= " AND (";
                
                if (! is_array($value)) {
                    
                    $value = array(
                        $value
                    );
                }
                
                foreach ($value as $myvalue) {
                    
                    $myvalue = addslashes($myvalue);
                    
                    if ($type == 'like') {
                        $sql .= " $key LIKE '%$myvalue%' OR ";
                    } else {
                        $sql .= " $key = '$myvalue' OR ";
                    }
                }
                
                $sql = $this->strLreplace('OR', '', $sql);
                
                $sql .= " ) ";
            }
        }
        
        $sql .= " ORDER BY id DESC ";
        
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        
        if ($offset) {
            $sql .= " OFFSET $offset ";
        }
        
        
//         $this->logger->debug("QUERY:     $sql ");
        
        return R::findAll($this->getType(), $sql, $bindings);
    }

    /**
     *
     * @param int $event_id            
     */
    public function countByparams($params = array())
    {
        $this->logger->debug("countByparams");
        $this->logger->debug(print_r($params, 1));
        
        $sql = "SELECT COUNT(id) FROM " . $this->getType() . " WHERE 1=1 ";
        
        foreach ($params as $key => $obj) {
            
            $value = null;
            if (isset($obj['value'])) {
                $value = $obj['value'];
            }
            
            $type = null;
            
            if (isset($obj['type'])) {
                $type = $obj['type'];
            }
            
            if (! empty($value)) {
                
                $sql .= " AND (";
                
                if (! is_array($value)) {
                    
                    $value = array(
                        $value
                    );
                }
                
                foreach ($value as $myvalue) {
                    
                    $myvalue = addslashes($myvalue);
                    
                    if ($type == 'like') {
                        $sql .= " $key LIKE '%$myvalue%' OR ";
                    } else {
                        $sql .= " $key = '$myvalue' OR ";
                    }
                }
                
                $sql = $this->strLreplace('OR', '', $sql);
                
                $sql .= " ) ";
            }
        }
        
//         $this->logger->debug("QUERY:     $sql ");
        
        $col = R::getCol($sql);
        
        return $col[0];
    }

    /**
     *
     * @param int $event_id            
     */
    public function countBy($segment, $dateStart)
    {
        $params = array();
        
        $where = '1=1 ';
        if ($dateStart !== '') {
            // create iso date
            $date_create = date("Y-m-d", strtotime($dateStart));
            $where .= "AND date_create >= $date_create";
        }
        
        $sql = "SELECT $segment, COUNT(id) FROM " . $this->getType() . " WHERE $where GROUP BY $segment";
        
        return R::getAssoc($sql);
    }

    /**
     *
     * @param int $event_id            
     */
    public function count($dateStart)
    {
        $params = array();
        
        $where = '1=1 ';
        if ($dateStart !== '') {
            // create iso date
            $date_create = date("Y-m-d", strtotime($dateStart));
            $where .= "AND date_create >= $date_create";
        }
        
        $sql = "SELECT COUNT(id) FROM " . $this->getType() . " WHERE $where";
        
        return R::getCol($sql);
    }

    /**
     */
    public function getDistinct($segment, $sorting = '')
    {
        $params = array();
        
        if ($sorting == '') {
            $sorting = ' ORDER BY ' . $segment . ' ASC';
        }
        
        $where = '1=1 ';
        
        $sql = "SELECT DISTINCT($segment) FROM " . $this->getType() . " WHERE $where GROUP BY $segment  $sorting ;";
        
        return R::getCol($sql);
    }

    /**
     *
     * @param int $event_id            
     */
    public function countByEvent($event_id = null)
    {
        $params = array();
        $sql = '';
        
        if ($event_id) {}
        
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