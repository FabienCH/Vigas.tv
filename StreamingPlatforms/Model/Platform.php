<?php
namespace Vigas\StreamingPlatforms\Model;
use \Vigas\Application\Application;

/**
* Abstract Class Platform.
* Gets data from a streaming platform API
*/
abstract class Platform
{
	use \Vigas\Application\Model\CurlRequest;
	
	/**
    * @var array A platform's API keys
    */
	protected $api_keys = [];
	
	/**
    * @var array Contains streams retrieved from the streaming platform
    */
	protected $streams = [];
	
	/**
    * @var array Contains followed streams retrieved from the streaming platform
    */
	protected $followed_streams = [];
	
	/**
    * @var array Contains games retrieved from the streaming platform
    */
	protected $games = [];
	
	/**
    * @var array Contains offline streamers name retrieved from the streaming platform
    */
    protected $offline_streamers = [];
		
	/**
    * Sets the streaming platform API keys
    */
	public function __construct()
    {
		$classname = explode('\\',get_class($this));
		$classname = end($classname);
		$this->api_keys = Application::getConfigFromXML(__DIR__.'/../config.xml', lcfirst($classname).'_key');
    }
	
	/**
    * Sets the streaming platform API URL
	* @param string $tagname The XML tag name attribute
	* @param array $vars Variables to replace in the API URL
    */
	public function getApiUrl($tagname, $vars = null)
    {
		$classname = explode('\\',get_class($this));
		$classname = end($classname);
		$api_urls = Application::getConfigFromXML(__DIR__.'/../config.xml', lcfirst($classname).'_url');
		if(is_array($api_urls))
		{
			$length = count($api_urls);
			foreach($api_urls as $url_key => $url_value)
			{
				if($url_key == $tagname)
				{
					if($vars !== null)
					{
						foreach($vars as $key => $value)
						{
							$url_value = str_replace($key, $value, $url_value);
						}
					}			
					$api_url = $url_value;
				}
			}
			return $api_url;
		}
		else
		{
			return $api_urls;
		}
		
		
    }
	
	/**
    * Gets streams from the streaming platform
	* @param string $url Platform API url to send the request to
    * @param string|null $http_header Http header to set for the request
	* @return array Streams retrieved from the platform
    */
	public function getStreamsFromPlatform($url, $http_header = null)
    {
		
	}
	
	/**
    * Gets games from the streaming platform
	* @param string $url Platform API url to send the request to
    * @param string|null $http_header Http header to set for the request
	* @return array Games retrieved from the platform
    */
	public function getGamesFromPlatform($url, $http_header = null)
    {
		
	}
	
	/**
    * Gets search from the streaming platform
	* @param string $query Research's keyword(s) entered by the user
    */
	public function getSearchFromPlatform($query)
    {
		
	}
    
	/**
    * @return array A platform's API keys
    */
    public function getApiKeys()
    {
        return $this->api_keys;
    }
	
	/**
    * @return array Contains streams retrieved from the streaming platform
    */
	public function getStreams()
    {
        return $this->streams;
    }
	
	/**
    * @return array Contains followed streams retrieved from the streaming platform
    */
	public function getFollowedStreams()
    {
        return $this->followed_streams;
    }
	
	/**
    * @return array Contains games retrieved from the streaming platform
    */
	public function getGames()
    {
        return $this->games;
    }
	
	/**
    * @return array Contains offline streamers name retrieved from the streaming platform
    */
	public function getOfflineStreamers()
    {
        return $this->offline_streamers;
    }
	
}