<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;
use App\Service\Mailing\IEmailRecipient;

class Lead extends AbstractDao implements IEmailRecipient
{

    const NAME = 'lead';

    /**
     */
    public function age()
    {
        $date = new \DateTime();
        $year = $date->format('Y');
        return $year - $this->year;
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
        $data = $this->export();
        return $data;
    }
    
    /*
     * (non-PHPdoc) @see \App\Service\Mailing\IEmailRecipient::getEmail()
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /*
     * (non-PHPdoc) @see \App\Service\Mailing\IEmailRecipient::getLabel()
     */
    public function getLabel()
    {
        return $this->name;
    }
}