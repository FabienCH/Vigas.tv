<?php
namespace Vigas\StreamingPlatforms\Model;
use \Vigas\Application\Application;

/**
* Abstract Class Platform
* Gets data from a streaming platform API
*/
abstract class Platform
{
	use \Vigas\Application\Model\CurlRequest;
	
	/**
    * @var array api_keys platform's API keys
    */
	protected $api_keys = [];
	
	/**
    * @var array streams contains streams retrieved from the streaming platform
    */
	protected $streams = [];
	
	/**
    * @var array followed_streams contains followed streams retrieved from the streaming platform
    */
	protected $followed_streams = [];
	
	/**
    * @var array games contains games retrieved from the streaming platform
    */
	protected $games = [];
	
	/**
    * @var array $offline_streamers contains offline streamers name
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
    * Sets the streaming platform API keys
    */
	public function getApiUrl($tagname, $vars = null)
    {
		$classname = explode('\\',get_class($this));
		$classname = end($classname);
		$api_urls = Application::getConfigFromXML(__DIR__.'/../config.xml', lcfirst($classname).'_url');
		$length = count($api_urls);
		foreach($api_urls as $url_key => $url_value)
		{
			if($url_key == $tagname)
			{
				if($vars != null)
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
	
	/**
    * Gets streams from the streaming platform
    */
	public function getStreamsFromPlatform($url, $http_header = null)
    {
		
	}
	
	/**
    * Gets followed streams from the streaming platform
    */
	public function getFollowedStreamsFromPlatform($url, $http_header = null)
    {
		
	}
	
	/**
    * Gets games from the streaming platform
    */
	public function getGamesFromPlatform($url, $http_header = null)
    {
		
	}
	
	/**
    * Gets search from the streaming platform
    */
	public function getSearchFromPlatform($query)
    {
		
	}
    
	/**
    * @return array api_keys platform's API keys
    */
    public function getApiKeys()
    {
        return $this->api_keys;
    }
	
	/**
    * @return array streams contains streams retrieved from the streaming platform
    */
	public function getStreams()
    {
        return $this->streams;
    }
	
	/**
    * @return array followed_streams contains followed streams retrieved from the streaming platform
    */
	public function getFollowedStreams()
    {
        return $this->followed_streams;
    }
	
	/**
    * @return array games contains games retrieved from the streaming platform
    */
	public function getGames()
    {
        return $this->games;
    }
	
	/**
    * @return array offline_streamers contains offline streamers name retrieved from the streaming platform
    */
	public function getOfflineStreamers()
    {
        return $this->offline_streamers;
    }
	
}