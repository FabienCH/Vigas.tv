<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\StreamingPlatforms\Model\Media;

/**
 * Class Stream extends Media.
 * Manages a stream
 */
class Stream extends Media
{
    /**
    * @var string The channel language
    */
    protected $channel_language;
    
    /**
    * @var string The channel name
    */
    protected $channel_name;
    
    /**
    * @var string The stream url
    */
    protected $stream_url;
    
    /**
    * @var string The chat url
    */
    protected $chat_url;
    
    /**
    * @var string The stream preview url (thumbnail)
    */
    protected $preview_url;
    
    /**
    * @var string The stream status (title)
    */
    protected $status;
    
    /**
    * @var string The channel display name (streamer name)
    */
    protected $channel_display_name;

    /**
    * @param int $id Stream id
    * @param string $game Game name
    * @param int $viewers Number of viewers watching this stream
    * @param string $channel_language The channel language
    * @param string $channel_name The channel name
    * @param string $stream_url The stream url
    * @param string $chat_url The chat url
    * @param string $preview_url The stream preview url (thumbnail)
    * @param string $status The stream status (title)
    * @param string $channel_display_name The channel display name (streamer name)
    * @param string $source The stream's streaming platform
    */
    public function __construct($id, $game, $viewers, $channel_language, $channel_name, $stream_url, $chat_url, $preview_url, $status, $channel_display_name, $source)
    {
        $this->id = $id;
        $this->game = $game;
        $this->viewers = $viewers;
        $this->channel_language = $channel_language;
        $this->channel_name = $channel_name;
        $this->stream_url = $stream_url;
        $this->chat_url = $chat_url;
        $this->preview_url = $preview_url;
        $this->status = $status;
        $this->channel_display_name = $channel_display_name;
        $this->source = $source;
    }

	/** 
    * @return string Returns the formated number of viewers
    */
    public function getFormatedViewers()
    {
		$viewers = $this->viewers;
		if($viewers > 9999)
		{
			$viewers = round($viewers/1000, 1)."k";
		}
		if($viewers > 99999)
		{
			$viewers = round($viewers/1000)."k";
		}
			
		return $viewers;
    }

    /** 
    * @return string Returns the channel language
    */
    public function getChannelLanguage()
    {
            return $this->channel_language;
    }

    /** 
    * @return string Returns the channel name
    */
    public function getChannelName()
    {
            return $this->channel_name;
    }

    /** 
    * @return string Returns the stream url
    */
    public function getStreamUrl()
    {
            return $this->stream_url;
    }

    /** 
    * @return string Returns the chat url
    */
    public function getChatUrl()
    {
            return $this->chat_url;
    }

    /** 
    * @return string Returns the stream preview url (thumbnail)
    */
    public function getPreviewUrl()
    {
            return $this->preview_url;
    }

    /** 
    * @return string Returns the stream status (title)
    */
    public function getStatus()
    {
            return $this->status;
    }

    /** 
    * @return string Returns the channel display name (streamer name)
    */
    public function getChannelDisplayName()
    {
            return $this->channel_display_name;
    }

    /** 
    * @return string Returns the stream's streaming platform
    */
    public function getSource()
    {
            return $this->source;
    }
}
