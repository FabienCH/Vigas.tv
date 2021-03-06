<?php
namespace Vigas\StreamingPlatforms\Model;

/**
 * Abstract Class Media.
 * A media is either a game or a stream
 */
abstract class Media
{
    /**
    * @var int The media id
    */
    protected $id;
    
    /**
    * @var string The game name
    */
    protected $game;
    
    /**
    * @var int The number of viewers
    */
    protected $viewers;
    
    /**
    * @var string The media's streaming platform
    */
    protected $source;

    /** 
    * @return int Returns the media id
    */
    public function getId()
    {
		return $this->id;
    }

    /** 
    * @return string Returns the game name
    */
    public function getGame()
    {
		return $this->game;
    }

    /** 
    * @return string Returns the number of viewers
    */
    public function getViewers()
    {
		return $this->viewers;
    }
}
