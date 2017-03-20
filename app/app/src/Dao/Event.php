<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;

class Event extends AbstractDao
{

    const NAME = 'event';

    /**
     *
     * @return Location
     */
    public function location()
    {
        return $this->location;
    }

    /**
     *
     * @return User
     */
    public function hostess()
    {
        return $this->user;
    }

    /**
     *
     * @return Store
     */
    public function store()
    {
        return $this->store;
    }

    /**
     *
     * @return string
     */
    public function signupUrl()
    {
        return $this->url() . '/signup';
    }

    /**
     *
     * @return string
     */
    public function url()
    {
        return '/event/' . $this->permalink;
    }
}