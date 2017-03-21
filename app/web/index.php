<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

require __DIR__ . '/../vendor/autoload.php';

$hostname = '';
if (isset($_SERVER['HTTP_HOST'])) {
    $hostname = $_SERVER['HTTP_HOST'];
}

// Instantiate the app
$settings = require __DIR__ . '/../app/settings.php';

// overload settings by hostname
if (! empty($hostname)) {
    define('CURRENT_DOMAIN',$hostname);
    $filename = __DIR__ . '/domains/' . $hostname . '/settings.php';
    if (file_exists($filename)) {
        $country_settings = require $filename;
        
        if (is_array($country_settings)) {
            $settings = array_merge_recursive($settings, $country_settings);
        }
    }
}

$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';

// Register middleware
require __DIR__ . '/../app/middleware.php';

// Register routes
require __DIR__ . '/../app/routes.php';

// Run!
$app->run();
