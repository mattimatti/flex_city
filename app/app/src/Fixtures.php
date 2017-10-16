<?php
namespace App;

use RedBeanPHP\R;
use App\Dao\Role;
use App\Dao\User;
use App\Dao\Event;
use App\Dao\Location;
use App\Dao\Store;
use App\Dao\Lead;

class Fixtures
{

    /**
     *
     * @var bool
     */
    private $freeze = false;

    /**
     *
     * @param bool $freeze            
     */
    function __construct($freeze = false)
    {
        $this->freeze = $freeze;
    }

    /**
     *
     * @param array $config            
     */
    public function openConnection($config)
    {
        R::addDatabase('fixtures', 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['user'], $config['password']);
        R::selectDatabase('fixtures');
    }

    /**
     */
    public function truncate()
    {
        R::exec("SET FOREIGN_KEY_CHECKS = 0;");
        $tables = R::getCol('SHOW TABLES');
        
        foreach ($tables as $table) {
            R::wipe($table);
        }
        
        R::exec("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /**
     */
    public function load()
    {
        $leadsCount = 300;
        
        $faker = \Faker\Factory::create();
        
        if ($this->freeze == true) {
            R::freeze($this->freeze);
            $this->truncate();
        } else {
            R::freeze($this->freeze);
            R::nuke();
        }
        
        // admin role and admin
        
        $adminRole = R::dispense(Role::NAME);
        $adminRole->role = "admin";
        R::store($adminRole);
        
        $admin = R::dispense(User::NAME);
        $admin->username = 'admin';
        $admin->setPassword('admin');
        $admin->addRole($adminRole);
        R::store($admin);
        
        // hostess role and hostess
        
        $hostessRole = R::dispense(Role::NAME);
        $hostessRole->role = "hostess";
        R::store($hostessRole);
        
        for ($h = 1; $h <= 2; $h ++) {
            
            $hostess = R::dispense(User::NAME);
            $hostess->username = "hostess$h";
            
            $hostess->name = $faker->firstNameFemale;
            $hostess->surname = $faker->lastName;
            
            $hostess->setPassword('hostess');
            $hostess->addRole($hostessRole);
            $hostess->sharedEventList = [];
            R::store($hostess);
            
            // locations
            
            for ($i = 0; $i < 3; $i ++) {
                
                $city = $faker->city;
                
                $location = R::dispense(Location::NAME);
                $location->name = $city;
                R::store($location);
                
                // stores
                
                for ($y = 1; $y < 3; $y ++) {
                    
                    $store = R::dispense(Store::NAME);
                    $store->name = "Store " . $faker->name;
                    R::store($store);
                    
                    // events
                    
                    for ($x = 1; $x < 3; $x ++) {
                        
                        $event = R::dispense(Event::NAME);
                        $event->name = "Event $city $x";
                        $event->location = $location;
                        $event->permalink = "event$x-$y-$i";
                        $event->store = $store;
                        R::store($event);
                        
                        for ($l = 1; $l < $leadsCount; $l ++) {
                            $lead = R::dispense(Lead::NAME);
                            $lead->name = $faker->firstName;
                            $lead->surname = $faker->lastName;
                            $lead->email = $faker->email;
                            $lead->day = $faker->dayOfMonth();
                            $lead->month = $faker->month();
                            $lead->year = $faker->year();
                            $lead->gender = $faker->randomElement(array(
                                'm',
                                'f'
                            ));
                            $lead->pp = 'y';
                            $lead->tc = 'y';
                            $lead->mkt = $faker->randomElement(array(
                                'y',
                                'n'
                            ));
                            $lead->date_create = R::isoDateTime();
                            
                            $lead->event = $event;
                            $lead->hostess = $hostess;
                            R::store($lead);
                        }
                        
                        // hostess events
                        
                        $hostess->sharedEventList[] = $event;
                    }
                }
            }
            
            R::store($hostess);
        }
    }

    /**
     * 
     */
    public function dump()
    {
        Debug::dump(array(
            R::exportAll(R::findAll(User::NAME)),
            R::exportAll(R::findAll(Role::NAME)),
            R::exportAll(R::findAll(Location::NAME)),
            R::exportAll(R::findAll(Event::NAME))
        ));
    }
}