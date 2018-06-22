<?php
session_start();


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

