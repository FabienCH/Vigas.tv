<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\StreamingPlatforms\Model\Stream;
use Vigas\StreamingPlatforms\Model\Game;
use Vigas\StreamingPlatforms\Model\Media;
use Vigas\Applciation\Model\CurlRequest;

/**
* Abstract Class MediasManager.
*/
abstract class MediasManager
{ 
     /**
    * @var array $medias_to_display contains medias to pass to the view 
    */
    protected $medias_to_display = [];
    /**
    * @var array $medias_array contains medias retrieved from streaming platform 
    */
    protected $medias_array = [];
    
    /**
    * @var int $media_id media id 
    */
    protected $media_id = 0;
        
    /**
    * Order medias by number of viewers from higher to lower
    * @param object Media $media1
    * @param object Media $media2
    */
    protected function oderByViewers(Media $media1, Media $media2)
    {
        if ($media1->getViewers() == $media2->getViewers()) {
            return 0;
        }
        return ($media1->getViewers() > $media2->getViewers()) ? -1 : 1;
    }
    
    /**
    * Add a Media object into media_array
    * @param object Media $media media to add to array
    */
    protected function addMedia(Media $media)
    {
        $id = $media->getId();
        if(!isset($this->medias_array[$id]))
        { 
            $this->medias_array[$id] = $media;
            $this->media_id++;
        }
    }
    
    /**
    * Create a JSON file with media_array data
    * @param string $path_file JSON file path
    */
    public function buildJsonFile($path_file)
    {	
        $serialized_medias=(serialize($this->medias_array));
        $json_medias_file = fopen($path_file, "w+");
        fwrite($json_medias_file, json_encode($serialized_medias));
        fclose($json_medias_file);
    }

    /**
    * Set medias_array from JSON file data
    * @param string $path_file JSON file path
    */
    public function setMediasArrayFromJSON($path_file)
    {
        $json_source = file_get_contents($path_file);
        $serialized_medias= json_decode($json_source, true);
        $this->medias_array=unserialize($serialized_medias);
    }
    
    /** 
    * @return int returns the media id
    */
    public function getMediaId()
    {
        return $this->media_id;
    }
    
    public function getMediasArray()
    {
        return $this->medias_array;
    }
    
}
