<?php

//base URL
define('PROTOCOL', '//');
define('DOMAIN', $_SERVER['HTTP_HOST']);
define('ROOT_FOLDER', str_replace('public', '', dirname($_SERVER['SCRIPT_NAME'])));
define('BASE_URL', PROTOCOL . DOMAIN . ROOT_FOLDER);

// Paths relative to the root directory
define('ROOT_PATH', realpath(__DIR__ . '/../'));
define('APP_PATH', ROOT_PATH . '/app');
define('CORE_PATH', ROOT_PATH . '/core');
define('VIEW_PATH', APP_PATH . '/Views');
define('ROUTE_PATH', ROOT_PATH . '/routes');
define('CONFIG_PATH', ROOT_PATH . '/config');


// Database Config
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'blog');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');