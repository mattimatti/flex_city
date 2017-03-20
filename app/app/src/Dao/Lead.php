<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;

class Lead extends AbstractDao
{

    const NAME = 'lead';

    
    
    /**
     * 
     * 
     */
    public function age()
    {
        return $this->year;
    }

    
    /**
     * 
     * 
     */
    public function hostess()
    {
        return $this->unbox()->fetchAs( 'user' )->hostess;
    }

    /**
     * @return Event
     */
    public function event()
    {
        return $this->event;
    }
}