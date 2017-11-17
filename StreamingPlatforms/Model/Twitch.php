<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\StreamingPlatforms\Model\Platform;

/**
* Class Twitch extends Platform
* Gets data from the Twitch API
*/ 
class Twitch extends Platform
{

    /**
    * Get streams from Twitch and add them to streams array
    * @param string $url Twitch API url to send the request to
    * @param string|null $http_header http header to set
	* @return array streams streams retrieved from Twitch
    */
	public function getStreamsFromPlatform($url, $http_header = null)
    {
        $response = $this->curlRequest($url, null, $http_header);
        $decode_flux = json_decode($response, true);
        if($decode_flux["_total"] != 0)
        {
            $API_streams = $decode_flux["streams"];
            foreach($API_streams as $stream)
            {
                $stream = new Stream($stream["_id"], $stream["game"], $stream["viewers"], $stream["channel"]["broadcaster_language"], $stream["channel"]["name"], "https://player.twitch.tv/?channel=".$stream["channel"]["name"], "https://www.twitch.tv/".$stream["channel"]["name"]."/chat?popout=", $stream["preview"]["large"], $stream["channel"]["status"], $stream["channel"]["display_name"], "Twitch");
				array_push($this->streams, $stream);
            }
        }
		return $this->streams;
	}
	
	
	/**
    * Get games from Twitch and add them to games array
    * @param string $url Twitch API url to send the request to
    * @param string|null $http_header http header to set
	* @return array games games retrieved from Twitch
    */
    public function getGamesFromPlatform($url, $http_header = null)
    {
        $response = $this->curlRequest($url, null, $http_header);
        $decode_flux = json_decode($response, true);
        $API_games = $decode_flux["top"];
        foreach($API_games as $game)
        {
            $game = new Game($game["game"]["_id"], $game["game"]["name"], $game["viewers"], "https://static-cdn.jtvnw.net/ttv-boxart/".str_replace(" ","%20",$game["game"]["name"])."-272x380.jpg","Twitch");
			array_push($this->games, $game);
        }
		return $this->games;
    }
	
	/**
    * Get search result from Twitch and add them to arrays
    * @param string $query research's keyword(s) entered by the user
    */
	public function getSearchFromPlatform($query)
    {
		$search_streams = $this->getStreamsFromPlatform('https://api.twitch.tv/kraken/search/streams?q='.urlencode($query).'&limit=50', array('Client-ID: '.$this->api_keys['client_id']));
       		
		$search_games = $this->curlRequest('https://api.twitch.tv/kraken/search/games?q='.urlencode($query).'&type=suggest&live=true', null, array('Client-ID: '.$this->api_keys['client_id']));
        $decode_flux = json_decode($search_games, true);
		$API_games = $decode_flux["games"];
        foreach($API_games as $game)
        {
            $game = new Game($game["_id"], $game["name"], '', "https://static-cdn.jtvnw.net/ttv-boxart/".str_replace(" ","%20",$game["name"])."-272x380.jpg","Twitch");
			array_push($this->games, $game);
        }
		
		$offline_streamer = $this->curlRequest('https://api.twitch.tv/kraken/users/'.urlencode($query), null, array('Client-ID: '.$this->api_keys['client_id']));
		$decode_offline_streamer = json_decode($offline_streamer, true);
        if(isset($decode_offline_streamer["display_name"]))
        {
			$streamer_is_offline = true;
			foreach($this->streams as $stream)
			{
				if(strtolower($stream->getChannelName()) == strtolower($decode_offline_streamer["display_name"]))
				{
					$streamer_is_offline = false;
				}
			}
			if($streamer_is_offline)
			{
				array_push($this->offline_streamers,array("name"=>$decode_offline_streamer["display_name"], "profile_link"=>"https://www.twitch.tv/".$decode_offline_streamer["display_name"], "source"=>"Twitch"));
			}      
        }  
	}
}
