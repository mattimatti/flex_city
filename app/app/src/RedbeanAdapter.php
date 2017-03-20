<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace App;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result as AuthenticationResult;
use RedBeanPHP\R;
use App\Dao\User;

/**
 * Authenticate through LDAP
 * Authorize through RDBMS, using Doctrine ORM
 *
 * @author marcelbonnet
 * @since 0.0.2
 */
class RedbeanAdapter extends AbstractAdapter
{

    /**
     * Performs authentication.
     *
     * @return AuthenticationResult Authentication result
     */
    public function authenticate()
    {
        $result = $this->_authenticateUser();
        if (! $result->isValid()) {
            return new AuthenticationResult(AuthenticationResult::FAILURE, $result->getIdentity(), $result->getMessages());
        }
        
        $userRoles = $this->findUserRoles();
        
        // return the identity data
        $user = array(
            "username" => $this->getIdentity(),
            "role" => $userRoles
        );
        return new AuthenticationResult(AuthenticationResult::SUCCESS, $user, array());
    }

    /**
     * password_hash("teste",PASSWORD_DEFAULT, [ "cost" => 15 ])
     *
     * @throws Exception
     * @return \Zend\Authentication\Result
     */
    private function _authenticateUser()
    {
        try {
            
            /* @var $user User */
            $user = $this->findUser($this->getIdentity());
            
            if (empty($user) || ! $user->isValidPassword($this->getCredential())) {
                
                return new AuthenticationResult(AuthenticationResult::FAILURE_CREDENTIAL_INVALID, array(), array(
                    'Invalid username and/or password provided'
                ));
            }
            
            // $currentHashOptions = array(
            // 'cost' => 10
            // );
            
            // $currentHashOptions = null;
            
            // $passwordNeedsRehash = password_needs_rehash($user->passwordHash, PASSWORD_DEFAULT, $currentHashOptions);
            
            // // FIXME: must rehash if needede
            // if ($passwordNeedsRehash === true) {
            // // try $em findby id , set e persist
            // }
            
            $userData = $user->export();
            unset($userData['passwordHash']);
            
            return new AuthenticationResult(AuthenticationResult::SUCCESS, $userData, array(
                'Authenticated through RDBMS'
            ));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Finds a user by $username
     *
     * @param string $username            
     * @return array
     * @throws Exception
     */
    private function findUser($username)
    {
        $params = array();
        $params[":username"] = $username;
        $user = R::findOne(User::NAME, "username = :username", $params);
        return $user;
    }

    /**
     * Perform a search of user's roles.
     *
     * @return array of roles
     */
    private function findUserRoles()
    {
        
        $params = array();
        $params[":username"] = $this->getIdentity();
        
        $sqla = array();
        $sqla[] = "SELECT";
        $sqla[] = "r.role as role";
        $sqla[] = "FROM";
        $sqla[] = "role_user ru";
        $sqla[] = "INNER JOIN user u ON u.id = ru.user_id";
        $sqla[] = "INNER JOIN role r ON r.id = ru.role_id";
        $sqla[] = "WHERE";
        $sqla[] = "u.username = :username";
        
        $sql = implode(' ', $sqla);
        $data = R::getAll($sql,$params);
        
        try {
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param string $opt
     *            return boolean
     */
    private function hasOption($opt)
    {
        return (! empty($this->options) && is_array($this->options) && array_key_exists($opt, $this->options));
    }

    /**
     * Get tableName.
     *
     * @return string tableName
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Get identityColumn.
     *
     * @return string identityColumn
     */
    public function getIdentityColumn()
    {
        return $this->identityColumn;
    }

    /**
     * Get credentialColumn.
     *
     * @return string credentialColumn
     */
    public function getCredentialColumn()
    {
        return $this->credentialColumn;
    }
}
