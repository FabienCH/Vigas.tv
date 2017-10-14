<?php
namespace Vigas\Application;

use Vigas\Application\Controller\AppController;
use Vigas\Application\Controller\HTTPRequest;
use Vigas\Application\Model\LinkedAccount;
use Vigas\Application\Model\UserManager;

use Vigas\StreamingPlatforms\Controller\MediaController;

/**
* Class Application
* Manage Application
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
    * Set base_url attribute - the website base url
    */
    public static function initializeApplication()
    {
		$xml_doc = new \DOMDocument;
		$xml_doc->load(__DIR__.'/config.xml');
		$element = $xml_doc->getElementsByTagName('config');
        self::$base_url = $element->item(0)->getAttribute('value');
		self::$http_request = new HTTPRequest;
    }
	
    /**
    * Get the user and his linked accounts
    * @param object User $user
    */
    public static function initializeSession($user)
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
    * Log in a file when a user log in
    * @param string $from login source (form or cookie)
    */
	public static function logUserLogin($from, $user)
	{
        $log_file = fopen('/home/vigas/logs/vigas/user_login.log', "a");
		$date = date("Y-m-d H:i:s", strtotime('now')); 
        fwrite($log_file, $user->getUsername().' login at '.$date.' from '.$from.' ('.self::$BASE_URL.")\r\n");
        fclose($log_file);
	}
    
	/**
    * Call the right controller according to the requested action
    */ 
    public static function getController()
    {
        if(!isset(self::$http_request->getGetData()['action']) || self::$http_request->getGetData()['action'] == 'games' || self::$http_request->getGetData()['action'] == 'streams-by-game' || (
            self::$http_request->getGetData()['action'] == 'following' && self::$user !== null && self::$user->getFirstLinkDone()==1)
            || self::$http_request->getGetData()['action'] == 'search')
        {
            $media_controller = new MediaController(self::$http_request);
			$media_controller->executeController();
			$media_controller->getView(self::$base_url);
        }
        else
        {
            $app_controller =  new AppController(self::$http_request);
			$app_controller->executeController();
			$app_controller->getView(self::$base_url);
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

