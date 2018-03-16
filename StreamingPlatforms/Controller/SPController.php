<?php
namespace Vigas\StreamingPlatforms\Controller;

use Vigas\StreamingPlatforms\Model\Twitch;
use Vigas\StreamingPlatforms\Model\Smashcast;
use Vigas\StreamingPlatforms\Model\MediasManager;
use Vigas\StreamingPlatforms\Model\SearchManager;
use Vigas\StreamingPlatforms\Model\TwitchAccount;
use Vigas\StreamingPlatforms\Model\SmashcastAccount;
use Vigas\Application\Application;
use Vigas\Application\Controller\HTTPRequest;
use Vigas\Application\Model\UserManager;
use Vigas\Application\View\View;

/**
* Class SPController
* Streaming Platforms Controller
*/
class SPController
{
	/**
    * @var array model_params parameters used by the model to get data
    */
    protected $model_params;
	
	/**
    * @var array model_data data got from the model
    */
    protected $model_data;
	
	/**
    * @var string navbar_method_name contains name of the SPController method used to get the navbar
    */
    protected $navbar_method_name;
	
	/**
    * @var string method_name contains name of the SPController method used to get the content
    */
	protected $method_name;
    
	/**
    * Sets parameters for the model and methods name
    */
    public function __construct()
    {
		$http_request = Application::getHTTPRequest();
		if(!is_null($http_request))
		{
			if(isset($http_request->getGetData()['action']) && $http_request->getGetData()['action'] == 'games')
			{
			   $this->model_params['streams_limit'] = 3;
			   $this->model_params['streams_offset'] = 0;
			   $this->model_params['source_array'] = ["All","Twitch","Smashcast"];
			   $this->model_params['games_limit'] = 24;
			   $this->model_params['games_offset'] = (isset($http_request->getGetData()['offset'])) ? $http_request->getGetData()['offset'] : 0;
			   $this->navbar_method_name = 'getStreams';     
			}
			elseif(!isset($http_request->getGetData()['action']) || $http_request->getGetData()['action'] == 'following' || $http_request->getGetData()['action'] == 'streams-by-game')
			{
				if(isset($http_request->getGetData()["source_json"]))
				{
					$this->model_params['source_array'] = $http_request->getGetData()["source_json"];
				}
				else
				{
					$this->model_params['source_array'] = ["All","Twitch","Smashcast"];
				}
			   $this->model_params['id-stream'] = (isset($http_request->getGetData()['id-stream'])) ? $http_request->getGetData()['id-stream'] : null;
			   $this->model_params['streams_limit'] = 36;
			   $this->model_params['streams_offset'] = (isset($http_request->getGetData()['offset'])) ? $http_request->getGetData()['offset'] : 0;
			   $this->model_params['games_limit'] = 6;
			   $this->model_params['games_offset'] = 0;
			   $this->navbar_method_name = 'getGames';
			} 
			if(isset($http_request->getGetData()['game']))
			{
				$this->model_params['games'] = $http_request->getGetData()['game'];
			}
			if(isset($http_request->getGetData()['action']) && $http_request->getGetData()['action'] == 'search')
			{
				$this->model_params['games_limit'] = 6;
				$this->model_params['games_offset'] = 0;
				$this->model_params['query'] = $http_request->getPostData()['query'];
				$this->navbar_method_name = 'getGames';
			}
			
			if(isset($http_request->getGetData()['action']))
			{
				$this->method_name = $this->setMethodName($http_request->getGetData()['action']);
			}
			else
			{
				$this->method_name = 'getStreams';
			}
		}    
    }
	
	/**
    * Sets the method name the controller will use
    * @param string $action the action url parameter
    * @return string the method name
    */
    public function setMethodName($action)
	{
        if(strpos($action, '-'))
        {
            $array = explode('-', $action);
            $action = 'get';
            foreach ($array as &$word)
            {
                $action .= ucfirst($word);
            }
            return $action;
        }
        else
        {
            return 'get'.ucfirst($action);
        }        
    }
   
    /**
    * Executes SPController methods to get content and navbar
    */
    public function executeController()
    {
		$ctrl_method_name = $this->method_name;
		$navbar_method_name = $this->navbar_method_name;
        $this->$ctrl_method_name();
		$this->$navbar_method_name();
    }
	
	/**
    * Executes SPController methods to get content and navbar
	* Creates the view and call View method and template
    */
	public function getView()
    {
		$view = new View($this->model_params, $this->model_data);
		if(isset($_GET['requested_by']) && $_GET['requested_by'] == 'ajax')
		{
			if($this->method_name == 'getGames')
			{
				$view->getGamesContent();
			}
			else
			{
				$view->getStreamsContent();
			}
		}
		else
		{
			$view_method_name = $this->method_name.'View';
			$view->$view_method_name();
			$view->getTemplate();
		}
    }
	
	/**
    * Gets streams to display for the all streams view
    */
    public function getStreams()
    { 
        $streams_manager = new MediasManager;
        $streams_manager->setMediasArrayFromJSON(__DIR__.'/../Model/data/streams.json');
        $this->model_data['streams_to_display'] = $streams_manager->getMediasToDisplay($this->model_params['streams_limit'], $this->model_params['streams_offset'], $this->model_params['source_array']);
    }  
	
	/**
    * Gets games to display for the all games view
    */
    public function getGames()
    {
        $games_manager = new MediasManager;
        $games_manager->setMediasArrayFromJSON(__DIR__.'/../Model/data/games.json');
        $this->model_data['games_to_display'] = $games_manager->getMediasToDisplay($this->model_params['games_limit'], $this->model_params['games_offset']);
    } 
    
	/**
    * Gets streams to display for the streams by game view
    */
    public function getStreamsByGame()
    {
        if(isset($this->model_params['games']))
        {
            $streams_manager = new MediasManager;
            $twitch_game = str_replace(" ", "%20", $this->model_params['games']);

            foreach($this->model_params['source_array'] as $source)
            {
                if($source == "Twitch")
                {
					$twitch = new Twitch;
                    $twitch->getStreamsFromPlatform($twitch->getApiUrl('get_streams_by_game', ['limit_val' => 100, 'offset_val' => 0, 'game_val' => $twitch_game]), array('Client-ID: '. $twitch->getApiKeys()['client_id']));
                    $twitch->getStreamsFromPlatform($twitch->getApiUrl('get_streams_by_game', ['limit_val' => 100, 'offset_val' => 100, 'game_val' => $twitch_game]), array('Client-ID: '. $twitch->getApiKeys()['client_id']));
					$streams_manager->setMediasArray($twitch->getStreams());
                }
                elseif($source=="Smashcast")
                {
					$smashcast = new Smashcast;
                    $smashcast->getStreamsFromPlatform($smashcast->getApiUrl('get_streams_by_game', ['limit_val' => 100, 'offset_val' => 0, 'game_val' => $this->model_params['games']]));
					$streams_manager->setMediasArray($smashcast->getStreams());
                }
            }

            $this->model_data['streams_to_display'] = $streams_manager->getMediasToDisplay($this->model_params['streams_limit'], $this->model_params['streams_offset'], $this->model_params['source_array']);
        }
    }
	
    /**
    * Gets streams to display for the following view
    */
    public function getFollowing()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(Application::getPlatformAccounts() !== null && Application::getUser()->getFirstLinkDone()==1)
        {
            $platform_accounts = Application::getPlatformAccounts();
            $user = Application::getUser();

            $streams_manager = new MediasManager;
            foreach($this->model_params['source_array'] as $source)
            {
                if($source=="Twitch" && isset($platform_accounts['twitch_data']))
                {
                    $twitch_token = $platform_accounts['twitch_data']->getToken();
                    $streams_manager->getTwitchStreams('https://api.twitch.tv/kraken/streams/followed', null, array('Client-ID: '.$this->getPlatformsKeys()['twitch']['client_id'], 'Authorization: OAuth '.$platform_accounts['twitch_data']->decryptToken($twitch_token)));
                }
                elseif($source=="Smashcast" && isset($platform_accounts['smashcast_data']))
                {
                    $streams_manager->getSmashcastFollowedStreams($platform_accounts['smashcast_data']->getUsername());
                }
            }				

            $this->model_data['streams_to_display'] = $streams_manager->getMediasToDisplay($this->model_params['streams_limit'], $this->model_params['streams_offset'], $this->model_params['source_array']);
        }
    }
    
	/**
    * Gets all data to display for the search view
    */
    public function getSearch()
    {
        if($this->model_params['query'] != '')
        {
			$streams_manager = new MediasManager;
			$games_manager = new MediasManager;
			
			$twitch = new Twitch;
            $twitch->getSearchFromPlatform($this->model_params['query']);
			$streams_manager->setMediasArray($twitch->getStreams());
			$games_manager->addGames($twitch->getGames());
			
			$smashcast = new Smashcast;
            $smashcast->getSearchFromPlatform($this->model_params['query']);
			$streams_manager->setMediasArray($smashcast->getStreams());
			$games_manager->addGames($smashcast->getGames());

			$this->model_data['streams_array'] = $streams_manager->getMediasToDisplay(100, 0, ["All","Twitch","Smashcast"]);
			$this->model_data['games_array'] = $games_manager->getMediasArray();
			$this->model_data['offline_streamers'] = array_merge($twitch->getOfflineStreamers(), $smashcast->getOfflineStreamers());
        }
    }
	
	public function getFirstLinkDone()
    {
        if(isset($this->post_params['first-link-done']))
        {
            $user_manager = new UserManager;
            $this->response['first_link_error'] = $user_manager->setFirstLinkDone();
        }
    }
    
    /**
    * Save streaming platforms token once identified
    */
    public function getSaveToken()
    {
		$get_params = Application::getHTTPRequest()->getGetData();
        if(Application::getUser() !== null)
        {
            if(isset($get_params['code']))
            {
				$twitch = new Twitch;
                $twitch_account = new TwitchAccount($twitch);

                $data = array('client_id' => $twitch->getApiKeys()['client_id'], 'client_secret' => $twitch->getApiKeys()['client_secret'], 'grant_type' => 'authorization_code', 'redirect_uri' => 'https://vigas.tv'.Application::getBaseUrl().'save-token', 'code' => $get_params['code'], 'state' => 'oauth2');

                $twitch_account->getTokenFromSource($data);
                $twitch_account->getUsernameFromSource();	
                $twitch_account->saveToDB(Application::getPDOconnection(), $twitch_account->getUsername(), Application::getUser()->getId());
                $twitch_account->getProfilePictureFromSource();
				var_dump($twitch_account);
            }

            if(isset($get_params['request_token']))
            {
				$smashcast = new Smashcast;
                $smashcast_account = new SmashcastAccount($smashcast);

                $data = array('request_token' => $get_params['request_token'], 'app_token' => $smashcast->getApiKeys()['app_token'], 'hash' => base64_encode($smashcast->getApiKeys()['app_token'].$smashcast->getApiKeys()['app_secret']));

                $smashcast_account->getTokenFromSource($data);
                $smashcast_account->getUsernameFromSource();	
                $smashcast_account->saveToDB(Application::getPDOconnection(), $smashcast_account->getUsername(), Application::getUser()->getId());
                $smashcast_account->getProfilePictureFromSource();
				var_dump($smashcast_account);
            }

            if(isset($get_params['authToken']))
            {
				$smashcast = new Smashcast;
                $smashcast_account = new SmashcastAccount($smashcast);

                $smashcast_account->setToken($get_params['authToken']);	
                $smashcast_account->getUsernameFromSource();
                $smashcast_account->saveToDB(Application::getPDOconnection(), $smashcast_account->getUsername(), Application::getUser()->getId());
                $smashcast_account->getProfilePictureFromSource();
				var_dump($smashcast_account);
            }
        }
		
        $user_manager = new UserManager;
        $_SESSION['platform_accounts'] = serialize($user_manager->getPlatformAccounts());
		var_dump($_SESSION['platform_accounts']);
		/*
        if(Application::getUser() !== null && Application::getUser()->getFirstLinkDone()==0)
        {
            header('Location: https://vigas.tv'.Application::getBaseUrl().'following');
        }
        else
        {
            header('Location: https://vigas.tv'.Application::getBaseUrl().'linked-account');
        }*/   
    }
    
	/**
    * @return array parameters used by the model to get data
    */
    public function getModelParams()
    {
        return $this->model_params;  
    }
    
	/**
    * @return array data got from the model
    */
    public function getModelData()
    {
        return $this->model_data;  
    }
}
