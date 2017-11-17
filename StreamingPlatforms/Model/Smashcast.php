<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\StreamingPlatforms\Model\Platform;

/**
* Class Smashcast extends Platform
* Gets data from the Smashcast API
*/
class Smashcast extends Platform
{
	
	/**
    * Get streams from Smashcast and add them to streams array
    * @param string $url Smashcast API url to send the request to
    * @param string|null $http_header http header to set
	* @return array streams streams retrieved from Smashcast
    */
    public function getStreamsFromPlatform($url, $http_header = null)
    {
        $response=$this->curlRequest($url, null, $http_header);

        $decode_flux = json_decode($response, true);
        if(isset($decode_flux["livestream"]))
        {
            $API_streams = $decode_flux["livestream"];
            foreach($API_streams as $stream)
            {
                $stream = new Stream($stream["media_id"], $stream["category_name"], $stream["media_views"], "", $stream["media_user_name"], "https://www.smashcast.tv/embed/".$stream["media_name"]."?autoplay=true","https://www.smashcast.tv/embedchat/".$stream["media_name"], "https://edge.sf.hitbox.tv/static/img/media/live/".$stream["media_name"]."_large_000.jpg", $stream["media_status"], $stream["media_display_name"], "Smashcast");
				array_push($this->streams, $stream);
            }
        }
		return $this->streams;
    }
	
	/**
    * Get games from Smashcast and add them to games array
    * @param string $url Smashcast API url to send the request to
    * @param string|null $http_header http header to set
	* @return array games games retrieved from Smashcast
    */
    public function getGamesFromPlatform($url, $http_header = null)
    {	
        $response=$this->curlRequest($url, null, $http_header);
        $decode_flux = json_decode($response, true);
        $API_games = $decode_flux["categories"];

        foreach($API_games as $game)
        {
            $game = new Game($game["category_id"], $game["category_name"], intval($game["category_viewers"]), "https://static-cdn.jtvnw.net/ttv-boxart/".str_replace(" ","%20",$game["category_name"])."-272x380.jpg","Smashcast");
			array_push($this->games, $game);
        }
		return $this->games;
    }
	
	/**
    * Get search result from Smashcast and add them to streams array
    * @param string $url Smashcast API url to send the request to
    * @param string|null $http_header http header to set
	* @return array streams streams retrieved from Smashcast
    */
	public function getSearchFromPlatform($query)
    {
		$search_streams = $this->getStreamsFromPlatform('https://api.smashcast.tv/media/live/list?search='.$query.'&limit=50');
       		
		$search_games = $this->curlRequest('https://api.smashcast.tv/games?q='.$query.'&limit=50&liveonly=true');
        
		$offline_streamer = $this->curlRequest('https://api.smashcast.tv/media/live/'.$query);
		$decode_offline_streamer = json_decode($offline_streamer, true);
        if(isset($decode_offline_streamer["livestream"]))
        {
            $stream = $decode_offline_streamer["livestream"][0];
            if($stream["media_is_live"] == 0)
            {
                array_push($this->offline_streamers,array("name"=>$stream["media_user_name"], "profile_link"=>"https://www.smashcast.tv/".$stream["media_name"],"source"=>"Smashcast"));
            }
        }  

	}
	
}