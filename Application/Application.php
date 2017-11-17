<?php
namespace Vigas\Application;

use Vigas\Application\Controller\AppController;
use Vigas\Application\Controller\HTTPRequest;
use Vigas\Application\Model\LinkedAccount;
use Vigas\Application\Model\UserManager;
use Vigas\StreamingPlatforms\Controller\SPController;

/**
* Class Application
* Manages Application
*/
abstract class Application
{
	/**
    * @var string base_url website base URL
    */
    protected static $base_url;
	
    /**
    * @var object PDO $pdo_connection
    */
    protected static $pdo_connection;
	
	/**
    * @var object HTTPRequest $http_request
    */
    protected static $http_request;
	
	/**
    * @var object HTTPResponse $http_response
    */
    protected static $http_response;
    
    /**
    * @var object User $user
    */
    protected static $user;

	/**
    * Sets base_url and http_request attributes
    */
    public static function initializeApplication()
    {
		self::$base_url = self::getConfigFromXML(__DIR__.'/config.xml', 'base_url');
		self::$http_request = new HTTPRequest;
    }
	
    /**
    * Gets the user and his linked accounts
    * @param object User $user
    */
    public static function initializeSession(User $user)
    {
        self::$user = $user;
        if(!isset($_SESSION['linked_accounts']))
        {
            $user_manager = new UserManager;
            $_SESSION['linked_accounts'] = serialize($user_manager->getAllLinkedAccounts(self::$getPDOconnection(), $user));
        }
        self::$linked_accounts = unserialize($_SESSION['linked_accounts']);
    }
	
	/**
    * Gets configuration from an XML file
	* @param string $conf_file path to set XML file
	* @param string $tag XML tag name
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
    * Writes in a file when a user log in
    * @param string $from login source (form or cookie)
	* @param object User $user
    */
	public static function logUserLogin($from, User $user)
	{
        $log_file = fopen(__DIR__.'/../../../logs/vigas/user_login.log', "a");
		$date = date("Y-m-d H:i:s", strtotime('now')); 
        fwrite($log_file, $user->getUsername().' login at '.$date.' from '.$from.' ('.self::$base_url.")\r\n");
        fclose($log_file);
	}
    
	/**
    * Executes controller and gets view according to the requested action
    */ 
    public static function getController()
    {
        if(!isset(self::$http_request->getGetData()['action']) || self::$http_request->getGetData()['action'] == 'games' || self::$http_request->getGetData()['action'] == 'streams-by-game' || (
            self::$http_request->getGetData()['action'] == 'following' && self::$user !== null && self::$user->getFirstLinkDone()==1)
            || self::$http_request->getGetData()['action'] == 'search')
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
    * @return string the webstie base url
    */
    public static function getBaseURL()
    {
        return self::$base_url;
    }
    
    /**
    * @return object PDO the PDO connection
    */
    public static function getPDOconnection()
    {
        return self::$pdo_connection;
    }
	
    /**
    * @return object HTTPRequest the HTTP request
    */
    public static function getHTTPRequest()
    {
        return self::$http_request;
    }
    
    /**
    * @return object the user
    */
    public static function getUser()
    {
        return self::$user;
    }
    
    /**
    * @return array user's linked accounts
    */
    public static function getLinkedAccounts()
    {
        return self::$linked_accounts;
    }
    
}


