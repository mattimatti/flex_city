<?php
namespace App;

use marcelbonnet\Slim\Auth\Acl\SlimAuthAcl;

class Acl extends SlimAuthAcl
{

    const GUEST = "guest";

    const ADMIN = "admin";

    const HOSTESS = "hostess";

    public function __construct()
    {
        // Application roles
        $this->addRole(self::GUEST);
        
        // Hostess extends guest
        $this->addRole(self::HOSTESS, self::GUEST);
        
        // admin allowed all.
        $this->addRole(self::ADMIN);
        $this->allow(self::ADMIN);
    }
}