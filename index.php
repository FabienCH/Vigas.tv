<?php
session_start();

require_once __DIR__.'/Application/Controller/Autoloader.php';

use Vigas\Application\Controller\Autoloader;
use Vigas\Application\Application;

Autoloader::register();

Application::initializeApplication();
Application::setPDOconnection();
Application::getController();

