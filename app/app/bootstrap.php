<?php

// Instantiate the app
$settings = require __DIR__ . '/../app/settings.php';

// overload settings by hostname
if (defined('CURRENT_DOMAIN')) {
    $filename = __DIR__ . '/domains/' . CURRENT_DOMAIN . '/settings.php';
    if (file_exists($filename)) {
        $country_settings = require $filename;
        
        if (is_array($country_settings)) {
            $settings = array_replace_recursive($settings, $country_settings);
        }
    }else{
        exit("error in db config: " . CURRENT_DOMAIN);
    }
}

$app = new \Slim\App($settings);

// Set up dependencies
require 'dependencies.php';

// Register middleware
require 'middleware.php';

// Register routes
require 'routes.php';