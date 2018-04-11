<?php
namespace Vigas\StreamingPlatforms\Model;

Use Vigas\StreamingPlatforms\Model\Media;

/**
 * Class Game extends Media.
 * Manages a streamed game
 */
class Game extends Media
{
    /**
    * @var string The game boxart's url
    */
    private $box;

    /**
    * @param int $id The game id
    * @param string $game The game name
    * @param int $viewers The number of viewers watching the game
    * @param string $box The game boxart's url
    * @param string $source The game's streaming platform
    */
    public function __construct($id, $game, $viewers, $box, $source)
    {
        $this->id = $id;
        $this->game = $game;
        $this->viewers = $viewers;
        $this->box = $box;
        $this->source = $source;
    }
    
    /** 
    * @return string The game boxart's url
    */
    public function getBox()
    {
        return $this->box;
    }
    
    /** 
    * @param string $box The game boxart's url to set
    */
    public function setBox($box)
    {
        $this->box = $box;
    }
    
    /** 
    * @param int $viewers Adds a number of viewers to the game
    */
    public function addViewers($viewers)
    {
        $this->viewers += $viewers;
    }
}
