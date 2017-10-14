<?php
session_start();

/********** error reporting *************/
error_reporting(E_ALL);
ini_set('display_errors', 'on');

ini_set('xdebug.default_enable', 'on');
ini_set('xdebug.show_local_vars', 1);
ini_set('xdebug.var_display_max_depth', 7);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
/**************************************/

/*
use Vigas\Controller\Autoloader;
use Vigas\Controller\Application;
use Vigas\Controller\Router;
use Vigas\Controller\UserManager;
use Vigas\Model\User;
*/

require_once __DIR__.'/Application/Controller/Autoloader.php';

use Vigas\Application\Controller\Autoloader;
use Vigas\Application\Application;

Autoloader::register();

Application::initializeApplication();
Application::setPDOconnection();
Application::getController();


/*
if(isset($_COOKIE['user']))
{
	if(!isset($_SESSION['first_log']))
	{
		Application::logUserLogin('cookie', unserialize($_COOKIE['user']));
	}
	$_SESSION['first_log'] = 1;
    $app->initializeSession(unserialize($_COOKIE['user']));
}
if(isset($_SESSION['user']))
{
    $app->initializeSession(unserialize($_SESSION['user']));
}

$router = new Router();
$router->getController();
 




    



