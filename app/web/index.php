<?php


// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}
// Define application environment
defined('ENVIRONMENT') || define('ENVIRONMENT', (getenv('ENVIRONMENT') ? getenv('ENVIRONMENT') : 'production'));


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




$hostname = '';
if (isset($_SERVER['HTTP_HOST'])) {
    $hostname = $_SERVER['HTTP_HOST'];
}
define('HOST_DOMAIN', $hostname);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/bootstrap.php';

// Run!
$app->run();
