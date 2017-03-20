<?php
namespace App\Dao;

use RedBeanPHP\R;

class UserRepository extends AbstractRepository
{
    
    /*
     * (non-PHPdoc) @see \App\Dao\AbstractRepository::getType()
     */
    public function getType()
    {
        return User::NAME;
    }

    /**
     *
     * @param string $username            
     * @return User
     */
    public function findByUsername($username)
    {
        $params = array();
        $params[':username'] = $username;
        $user = R::findOne($this->getType(), "username = :username", $params);
        return $user;
    }

    /**
     *
     * @param string $username            
     * @return User
     */
    public function findByRole($role)
    {
        $params = array();
        $params[':role'] = $role;
        
        $sqla = array();
        $sqla[] = 'SELECT';
        $sqla[] = 'u.*';
        $sqla[] = 'FROM';
        $sqla[] = 'role_user ur';
        $sqla[] = 'INNER JOIN user u ON u.id = ur.user_id';
        $sqla[] = 'INNER JOIN role r ON r.id = ur.role_id';
        $sqla[] = 'WHERE';
        $sqla[] = 'r.role = :role';
        
        $sql = implode(' ', $sqla);
        
        $rows = R::getAll($sql, $params);
        $users = R::convertToBeans($this->getType(), $rows);
        
        return $users;
    }
}