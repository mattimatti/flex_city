<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;

class AbstractDao extends SimpleModel
{

    public function label()
    {
        return $this->name;
    }
}