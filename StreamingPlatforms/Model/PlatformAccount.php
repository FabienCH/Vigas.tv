<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\Application\Model\CurlRequest;
use Vigas\Application\Controller\Encryption;
use Vigas\Application\Application;

/**
* Class PlatformAccount.
* Gets and manage account from streaming platform, interact with database
*/
abstract class PlatformAccount
{
    use CurlRequest;
    use Encryption;
    
    /**
    * @var object The streaming platform
    */
    protected $platform;
    
    /**
    * @var string The streaming platform account username
    */
    protected $username;
    
    /**
    * @var string The authentication token given by the streaming platform
    */
    protected $token;

    /**
    * @var string The the streaming platform profil picture URL
    */
    protected $profil_picture_url;
    
    /**
    * @param string $platform The streaming platform
    */
    public function __construct($platform)
    {
        $this->platform = $platform;
    }

	/** 
    * @return object Returns the streaming platform
    */
    public function getPlatform()
    {
        return $this->platform;
    }
	
    /** 
    * @return string Returns the streaming platform account username
    */
    public function getUsername()
    {
        return $this->username;
    }

    /** 
    * @return string Returns the authentication token given by the streaming platform
    */
    public function getToken()
    {
        return $this->token;
    }
    
    /** 
    * @return string Returns the streaming platform profil picture URL
    */
    public function getProfilPictureUrl()
    {
        return $this->profil_picture_url;
    }

    /** 
    * @param string $token The authentication token given by the streaming platform
    */
    public function setToken($token)
    {
        $this->token = $token;
    }
}
