<?php
namespace App\Dao;

use RedBeanPHP\SimpleModel;

class User extends AbstractDao
{

    const NAME = 'user';

    public function label()
    {
        return $this->username;
    }

    
    
    public function events()
    {
        return $this->sharedEventList;
    }

    /**
     * Set a password hashed
     *
     * @param string $password            
     */
    public function setPassword($password)
    {
        if (! $this->passwordHash) {
            $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    /**
     *
     * @param unknown $password            
     */
    public function isValidPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }

    /**
     * Add a shared role
     *
     * @param Role $role            
     */
    public function addRole($role)
    {
        $this->unbox()->sharedRoleList[] = $role;
    }
}