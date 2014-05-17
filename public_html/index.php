<?php
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

switch(APPLICATION_ENV)
{
	case 'local'		:	define('BASE_PATH', 'http://local.nomsterz.com'); break;
	case 'development'	:	define('BASE_PATH', 'http://local.nomsterz.com'); break;
	case 'testing'		:	define('BASE_PATH', 'http://unit-test.nomsterz.com'); break;
	case 'staging'		:	define('BASE_PATH', 'http://staging.nomsterz.com'); break;
	case 'optimization'	:	define('BASE_PATH', 'http://faster.nomsterz.com'); break;

	default	:	define('BASE_PATH', 'http://www.nomsterz.com');
}
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
