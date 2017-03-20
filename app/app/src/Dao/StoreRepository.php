<?php
namespace App\Dao;

use RedBeanPHP\R;
use App\Debug;

class StoreRepository extends AbstractRepository
{
    
    /*
     * (non-PHPdoc) @see \App\Dao\AbstractRepository::getType()
     */
    public function getType()
    {
        return Store::NAME;
    }
}