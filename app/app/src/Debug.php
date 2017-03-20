<?php
namespace App;

class Debug
{

    public static function dump($obj)
    {
        echo "<pre>";
        print_r($obj);
        exit();
    }
}