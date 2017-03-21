<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;

class AbstractDao extends SimpleModel
{

    public function label()
    {
        return $this->name;
    }

    
    public function export()
    {
        return $this->unbox()->export();
    }
}