<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\Application\Model\CurlRequest;
use Vigas\Application\Controller\Encryption;
use Vigas\Application\Application;

/**
* Class PlatformAccount
* Get and manage account from streaming platform, interact with database
*/
abstract class PlatformAccount
{
    use CurlRequest;
    use Encryption;
    
    /**
    * @var string $platform the streaming platform
    */
    protected $platform;
    
    /**
    * @var string $username the streaming platform account username
    */
    protected $username;
    
    /**
    * @var string $token the token given by the streaming platform once the user is logged
    */
    protected $token;

    /**
    * @var string $profil_picture_url the profil picture url
    */
    protected $profil_picture_url;
    
    /**
    * @param string $platform the streaming platform
    */
    public function __construct($platform)
    {
        $this->platform = $platform;
    }

    /** 
    * @return string returns the streaming platform account username
    */
    public function getUsername()
    {
        return $this->username;
    }

    /** 
    * @return string returns the token given by the streaming platform once the user is logged
    */
    public function getToken()
    {
        return $this->token;
    }
    
    /** 
    * @return string returns the streaming platform profil picture
    */
    public function getProfilPictureUrl()
    {
        return $this->profil_picture_url;
    }

    /** 
    * @param string $token the token given by the streaming platform once the user is logged
    */
    public function setToken($token)
    {
        $this->token = $token;
    }
}
