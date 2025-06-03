<?php
/**
 * Application Configuration
 * 
 * Defines constants for base URLs and database connection settings.
 */
//Base URL path relative to document root (ex: /blog-app/public)
define('BASE_URL_PATH', dirname($_SERVER['SCRIPT_NAME']));// 

//Database Config
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');