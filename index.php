<?php
session_start();

<<<<<<< HEAD
=======
/********** error reporting *************/
error_reporting(E_ALL);
ini_set('display_errors', 'on');

ini_set('xdebug.default_enable', 'on');
ini_set('xdebug.show_local_vars', 1);
ini_set('xdebug.var_display_max_depth', 7);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
/**************************************/

>>>>>>> 632e949003b651121bf1b9d0df086fa3294a0307

require_once __DIR__.'/Application/Controller/Autoloader.php';

use Vigas\Application\Controller\Autoloader;
use Vigas\Application\Application;

Autoloader::register();

Application::setPDOconnection();
Application::initializeApplication();

if(isset($_COOKIE['user']) && !isset($_SESSION['user']))
{
    Application::initializeSession(unserialize($_COOKIE['user']));
	Application::getUser()->logUserLogin(Application::getPDOconnection(), 'cookie');
	$_SESSION['user'] = $_COOKIE['user'];
	
}
if(isset($_SESSION['user']))
{
    Application::initializeSession(unserialize($_SESSION['user']));
}

Application::getController();

