#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands\LoadFixturesCommand;
use App\Commands\DefaultCommand;
use App\Commands\SendMailCommand;
use App\Commands\LoadSchemaCommand;
use App\Commands\InsertAdminCommand;

$application = new Application();

// define('CURRENT_DOMAIN', $hostname);

// bootstrap
require __DIR__ . '/../app/bootstrap.php';

// $defaultCommand = new DefaultCommand();
// $application->add($defaultCommand);
// $application->setDefaultCommand($defaultCommand->getName());

// $application->add(new SendMailCommand($app->getContainer()));

// Define application environment
defined('ENVIRONMENT') || define('ENVIRONMENT', (getenv('ENVIRONMENT') ? getenv('ENVIRONMENT') : 'production'));
// defined('ENVIRONMENT') || define('ENVIRONMENT', (getenv('ENVIRONMENT') ? getenv('ENVIRONMENT') : 'development'));

//
//
$fixturesCommand = new LoadFixturesCommand();
$fixturesCommand->setSlim($app);
$application->add($fixturesCommand);

//
//
$insertAdminCommand = new InsertAdminCommand();
$insertAdminCommand->setSlim($app);
$application->add($insertAdminCommand);

//
//
//
$schemaCommand = new LoadSchemaCommand();
$schemaCommand->setSlim($app);
$application->add($schemaCommand);

$application->run();