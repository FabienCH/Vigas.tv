<?php
namespace Vigas\Application\Controller;

/**
* Class Autoloader.
* Manage autoload
*/
class Autoloader
{
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    
    static function autoload($class)
    {
        if(strpos($class, 'Vigas\\')==0)
        {
            $class = str_replace('Vigas\\', '', $class);
            $class = str_replace('\\', '/', $class);
            if(is_readable($class.'.php'))
            {
                require_once $class.'.php';
            }
			else
			{
				require_once '/home/vigas/public_html/dev2/'.$class.'.php'; 
			}
        }
    }
    
}
