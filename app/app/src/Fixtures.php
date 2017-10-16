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
        $leadsCount = 700;
        
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
        
        for ($l = 1; $l < $leadsCount; $l ++) {
            $lead = R::dispense(Lead::NAME);
            $lead->name = $faker->firstName;
            $lead->surname = $faker->lastName;
            $lead->address = $faker->address;
            $lead->country = $faker->randomElement(array(
                'IT',
                'ES',
                'UK',
                'DE'
            ));
            $lead->lang = $faker->countryCode;
            $lead->email = $faker->email;
            $lead->model = "model " . $faker->numberBetween(1, 6);
            $lead->prize = $faker->randomElement(array(
                'Non Vinto',
                'Premio X',
                'Premio Y',
                'Premio Z'
            ));
            $lead->day = "dd " . $faker->numberBetween(1, 30);
            $lead->month = "mm " . $faker->numberBetween(1, 12);
            $lead->hour = $faker->time('H');
            $lead->gender = $faker->randomElement(array(
                'm',
                'f'
            ));
            
            $lead->pp = 'y';
            $lead->mvf = $faker->randomElement(array(
                'y',
                'n'
            ));
            $lead->mgr = $faker->randomElement(array(
                'y',
                'n'
            ));
            $lead->date_create = $faker->iso8601;
            
            R::store($lead);
        }
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