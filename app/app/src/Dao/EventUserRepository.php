<?php
namespace App\Dao;

use RedBeanPHP\R;
use App\Debug;

class EventUserRepository extends AbstractRepository
{
    
    /*
     * (non-PHPdoc) @see \App\Dao\AbstractRepository::getType()
     */
    public function getType()
    {
        return EventUser::NAME;
    }
}