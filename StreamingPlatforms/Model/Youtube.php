<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\StreamingPlatforms\Model\Platform;

/**
* Class Youtube extends Platform.
* Gets data from the Youtube API
*/ 
class Youtube extends Platform
{

    /**
    * Gets streams from Youtube and add them to streams array
    * @param string $url Youtube API url to send the request to
    * @param string|null $http_header Http header to set for the request
	* @return array Streams retrieved from Youtube
    */
	public function getStreamsFromPlatform($url, $http_header = null)
    {
        $response = $this->curlRequest($url, null, $http_header);
        $decode_flux = json_decode($response, true);
        if(isset($decode_flux["items"]))
        {
            $API_streams = $decode_flux["items"];
            foreach($API_streams as $stream)
            {
				$viewers = isset($stream["liveStreamingDetails"]["concurrentViewers"]) ? $stream["liveStreamingDetails"]["concurrentViewers"] : 0;
                $stream = new Stream($stream["id"], "", $viewers, "", $stream["snippet"]["channelTitle"], "https://gaming.youtube.com/embed/".$stream["id"]."?autoplay=1", "https://gaming.youtube.com/live_chat?v=".$stream["id"]."&is_popout=1", $stream["snippet"]["thumbnails"]["high"]["url"], $stream["snippet"]["title"], $stream["snippet"]["channelTitle"], "Youtube");
				array_push($this->streams, $stream);
            }
        }
		return $this->streams;
	}
	
	/**
    * Gets streams id from Youtube
    * @param string $url Youtube API url to send the request to
    * @param string|null $http_header Http header to set for the request
	* @return string a string of streams id separated with comma
    */
	public function getStreamsIdsFromPlatform($url, $http_header = null)
    {
        $response = $this->curlRequest($url, null, $http_header);
        $decode_flux = json_decode($response, true);
        if(isset($decode_flux["items"]))
        {
			$streams_ids = $decode_flux["items"];
			$string="";
			$i = 0;
			$lenght = count($streams_ids);
			foreach($streams_ids as $item)
			{
				$string .= $item["id"]["videoId"];
				if($i != $lenght -1)
				{
					$string .= ',';
				} 
				$i++;
			}
        }
		return $string;
	}
	
}
