#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands\LoadFixturesCommand;
use App\Commands\DefaultCommand;
use App\Commands\SendMailCommand;
use App\Commands\LoadSchemaCommand;

$application = new Application();

// define('CURRENT_DOMAIN', $hostname);

// bootstrap
require __DIR__ . '/../app/bootstrap.php';

// $defaultCommand = new DefaultCommand();
// $application->add($defaultCommand);
// $application->setDefaultCommand($defaultCommand->getName());

// $application->add(new SendMailCommand($app->getContainer()));

$fixturesCommand = new LoadFixturesCommand();
$fixturesCommand->setSlim($app);
$application->add($fixturesCommand);


$schemaCommand = new LoadSchemaCommand();
$schemaCommand->setSlim($app);
$application->add($schemaCommand);


$application->run();