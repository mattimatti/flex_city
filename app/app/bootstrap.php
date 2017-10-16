<?php
use App\Debug;

// load generic settings
$settings = require __DIR__ . '/../app/settings.php';

// overload settings by hostname

// define('CURRENT_DOMAIN', HOST_DOMAIN);

$app = new \Slim\App($settings);

// Set up dependencies
require 'dependencies.php';

// Register middleware
require 'middleware.php';

// Register routes
require 'routes.php';
