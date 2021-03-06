<?php
namespace Vigas\Application\Controller;

/**
* Class Autoloader.
* Manages autoload
*/
class Autoloader
{
	/**
    * Registers autoload
    */
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    
	/**
    * Sets path and requires PHP file
	* @param array $class The class name to load
    */
    public static function autoload($class)
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
				require_once '/home/vigas/public_html/'.$class.'.php'; 
			}
        }
    }
    
}
