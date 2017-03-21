#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Commands\LoadFixturesCommand;
use App\Commands\DefaultCommand;
use App\Commands\SendMailCommand;

// Instantiate the app
$settings = require __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';

// Register middleware
require __DIR__ . '/../app/middleware.php';

// Register routes
require __DIR__ . '/../app/routes.php';

$application = new Application();

// $defaultCommand = new DefaultCommand();
// $application->add($defaultCommand);
// $application->setDefaultCommand($defaultCommand->getName());

$application->add(new SendMailCommand($app->getContainer()));
$application->add(new LoadFixturesCommand());
$application->run();