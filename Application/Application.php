<?php
namespace Vigas\Application;

use Vigas\Application\Controller\AppController;
use Vigas\Application\Controller\HTTPRequest;
use Vigas\Application\Model\PlatformAccount;
use Vigas\Application\Model\UserManager;
use Vigas\Application\Model\User;
use Vigas\StreamingPlatforms\Controller\SPController;

/**
* Class Application.
* Manages the application
*/
abstract class Application
{
	/**
    * @var string Website's base URL ("/dev" for development environment and "/" for production)
    */
    protected static $base_url;
	
	/**
    * @var array URL path from the config.xml file
    */
    protected static $path;
	
    /**
    * @var \PDO Database connection configuration
    */
    protected static $pdo_connection;
	
	 /**
    * @var array SMTP configuration used to send email
    */
    protected static $smtp_conf;
	
	/**
    * @var HTTPRequest The HTTP request
    */
    protected static $http_request;
    
    /**
    * @var User Object that contain the authenticated user
    */
    protected static $user;
	
	/**
    * Sets base_url, smtp_conf and http_request attributes
    */
    public static function initializeApplication()
    {
		self::$base_url = self::getConfigFromXML(__DIR__.'/config.xml', 'base_url');
		self::$path = self::getConfigFromXML(__DIR__.'/config.xml', 'path');
		self::$smtp_conf = self::getConfigFromXML(__DIR__.'/config.xml', 'smtp');
		self::$http_request = new HTTPRequest;
    }
	
    /**
    * Sets the user and get the list his streaming platform accounts
    * @param User $user Object that contain the authenticated user
    */
    public static function initializeSession(User $user)
    {
        self::$user = $user;
		$user_manager = new UserManager;
		$_SESSION['platform_accounts'] = serialize($user_manager->getPlatformAccountsFromDB(self::$pdo_connection, $user));
    }
	
	/**
    * Gets configuration from an XML file
	* @param string $conf_file Path to the XML file
	* @param string $tag XML tag name
	* @return mixed The requested configuration informations
    */
    public static function getConfigFromXML($conf_file, $tag)
    {
		$xml_doc = new \DOMDocument;
		$xml_doc->load($conf_file);
		$elements = $xml_doc->getElementsByTagName($tag);
		if($elements->length == 1)
		{
			return $elements->item(0)->getAttribute('value');
		}
		elseif($elements->length > 1)
		{
			$config = [];
			for($i=0; $i<$elements->length; $i++)
			{
				$config[$elements->item($i)->getAttribute('name')] = $elements->item($i)->getAttribute('value');
			}
			return $config;
		}	
    }
	
	/**
    * Redirects configuration from an XML file
	* @param string $conf_file Path to the XML file
	* @param string $tag XML tag name
	* @return mixed The requested configuration informations
    */
    public static function templateRequired()
    {
		
		if(isset(self::$http_request))
		{
			if(!in_array(self::$http_request->getGetData()['action'], array("login", "signup", "forgot-password", "reset-password")))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
    }
    
	/**
    * Executes controller and gets the view according to the requested action
    */ 
    public static function getController()
    {
		$get_spcontroller = array('games', 'streams-by-game', 'search', 'following', 'linked-accounts', 'save-token', 'first-link-done');
        if(!isset(self::$http_request->getGetData()['action']) || in_array(self::$http_request->getGetData()['action'], $get_spcontroller))
        {
            $sp_controller = new SPController();
			$sp_controller->executeController();
			$sp_controller->getView();
			
        }
        else
        {
            $app_controller =  new AppController();
			$app_controller->executeController();
			$app_controller->getView();
        }
    }
	
    /**
    * Initialize PDO connection
    */
    public static function setPDOconnection()
    {
		$xml_doc = new \DOMDocument;
		$xml_doc->load(__DIR__.'/config.xml');
		$elements = $xml_doc->getElementsByTagName('database');
		
		foreach ($elements as $element)
		{
			$database[$element->getAttribute('name')] = $element->getAttribute('value');
		}
		
        self::$pdo_connection = new \PDO(
		$database['database'].':host='.$database['host'].';dbname='.$database['dbname'].';charset='.$database['charset'],
		$database['username'],
		$database['password']);
    }
	
	/**
    * @return string Website's base URL ("/dev" for development environment and "/" for production)
    */
    public static function getBaseURL()
    {
        return self::$base_url;
    }
	
	/**
    * @return array URL path from the config.xml file
    */
    public static function getPath()
    {
        return self::$path;
    }
	
	/**
    * @return PDO the PDO connection
    */
    public static function getPDOconnection()
    {
        return self::$pdo_connection;
    }
	
	/**
    * @return array SMTP configuration used to send email
    */
    public static function getSMTPConf()
    {
        return self::$smtp_conf;
    }
	
    /**
    * @return HTTPRequest the HTTP request
    */
    public static function getHTTPRequest()
    {
        return self::$http_request;
    }
    
    /**
    * @return User Object that contain the authenticated user
    */
    public static function getUser()
    {
        return self::$user;
    }
    
}
