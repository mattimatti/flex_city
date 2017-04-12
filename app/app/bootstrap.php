<?php
use App\Debug;
// Instantiate the app
$settings = require __DIR__ . '/../app/settings.php';

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

// /home/user/flex_city/app/app
// overload settings by hostname

if (defined('HOST_DOMAIN')) {
    
    if (isset($_SERVER['REQUEST_URI'])) {
        
        $uri = $_SERVER['REQUEST_URI'];
        
        if (startsWith($uri, '/it_ch')) {
            define('CURRENT_DOMAIN', 'flexinthecity.timberland.ch.it');
        }
        
        if (startsWith($uri, '/de_ch')) {
            define('CURRENT_DOMAIN', 'flexinthecity.timberland.ch.de');
        }
        
        if (startsWith($uri, '/fr_ch')) {
            define('CURRENT_DOMAIN', 'flexinthecity.timberland.ch.fr');
        }
    }
    
    if (! defined('CURRENT_DOMAIN')) {
        define('CURRENT_DOMAIN', HOST_DOMAIN);
    }
} else {
    exit('invalid host');
}



$filename = __DIR__ . '/../web/domains/' . CURRENT_DOMAIN . '/settings.php';
if (file_exists($filename)) {
    $country_settings = require $filename;
    
    if (ENVIRONMENT != 'development') {
        unset($country_settings['database']);
    }
    
    if (is_array($country_settings)) {
        $settings = array_replace_recursive($settings, $country_settings);
    }
    
} else {
    exit("error in db config: " . CURRENT_DOMAIN . " - ");
}

$app = new \Slim\App($settings);

// Set up dependencies
require 'dependencies.php';

// Register middleware
require 'middleware.php';

// Register routes
require 'routes.php';
