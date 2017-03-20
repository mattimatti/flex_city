<?php
namespace App\Service;

use App\Dao\UserRepository;
use App\Dao\User;
use App\Dao\RoleRepository;

class UserService
{

    /**
     *
     * @var UserRepository
     */
    protected $userRepo;

    /**
     *
     * @var RoleRepository
     */
    protected $roleRepo;

    /**
     *
     * @param UserRepository $leadRepo            
     */
    function __construct(UserRepository $userRepo, RoleRepository $roleRepo)
    {
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
    }

    /**
     *
     * @return User
     */
    public function create(array $params, $password, $role)
    {
        
        /* @var $user User */
        $user = $this->getRepo()->create($params);
        $user->setPassword($password);
        $this->getRepo()->store($user);
        
        $this->addRole($user, $role);
        
        return $user;
    }

    /**
     *
     * @return User
     */
    public function addRole($user, $roleStr)
    {
        $role = $this->roleRepo->findByAclString($roleStr);
        $user->addRole($role);
        $this->getRepo()->store($user);
    }

    /**
     *
     * @return UserRepository
     */
    public function getRepo()
    {
        return $this->userRepo;
    }

    /**
     *
     * @param \App\Dao\UserRepository $userRepo            
     */
    public function setRepo($userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     *
     * @return \App\Dao\RoleRepository
     */
    public function getRoleRepo()
    {
        return $this->roleRepo;
    }

    public function setRoleRepo(UserRepository $roleRepo)
    {
        $this->roleRepo = $roleRepo;
        return $this;
    }
}