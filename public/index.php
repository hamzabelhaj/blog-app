<?php
/**
 * Application Entry Point
 * Loads Composer's autoloader for class autoloading and starts the application's lifecycle
 */

 //load composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

use \Core\Application;

$app = new Application();
$app->run();