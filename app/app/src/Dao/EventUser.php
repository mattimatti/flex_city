<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;

class EventUser extends AbstractDao
{

    const NAME = 'event_user';

    /**
     */
    public function user()
    {
        return $this->user;
    }

    /**
     */
    public function event()
    {
        return $this->event;
    }
}