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

    public function load()
    {
        $leadsCount = 200;
        
        $faker = \Faker\Factory::create();
        
        R::freeze(FALSE);
        R::nuke();
        
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
        
        $hostess = R::dispense(User::NAME);
        $hostess->username = 'hostess';
        $hostess->setPassword('hostess');
        $hostess->addRole($hostessRole);
        $hostess->sharedEventList = array();
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
                    
                    for ($x = 1; $x < $leadsCount; $x ++) {
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

    /**
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