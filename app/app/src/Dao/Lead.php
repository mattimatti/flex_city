<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;

class Lead extends AbstractDao
{

    const NAME = 'lead';

    /**
     */
    public function age()
    {
        return $this->year;
    }

    /**
     */
    public function hostess()
    {
        return $this->unbox()->fetchAs('user')->hostess;
    }

    /**
     *
     * @return Event
     */
    public function event()
    {
        return $this->event;
    }

    /**
     *
     * @return array
     */
    public function getView()
    {
        $data = array();
        $data['id'] = $this->id;
        $data['namesurname'] = $this->name . " " . $this->surname;
        $data['email'] = $this->email;
        $data['age'] = $this->age();
        $data['year'] = $this->year;
        $data['month'] = $this->month;
        $data['day'] = $this->day;
        $data['location'] = $this->event()
            ->location()
            ->label();
        $data['store'] = $this->event()
            ->store()
            ->label();
        $data['hostess'] = $this->hostess()->label();
        $data['date_create'] = $this->date_create;
        
        return $data;
    }
}