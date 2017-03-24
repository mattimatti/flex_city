<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}


$hostname = '';
if (isset($_SERVER['HTTP_HOST'])) {
    $hostname = $_SERVER['HTTP_HOST'];
}
define('CURRENT_DOMAIN',$hostname);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/bootstrap.php';

// Run!
$app->run();
