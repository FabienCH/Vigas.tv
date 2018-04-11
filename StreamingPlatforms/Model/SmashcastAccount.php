<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\Application\Model\CurlRequest;
use Vigas\Application\Controller\Encryption;
use Vigas\Application\Application;
use Vigas\StreamingPlatforms\Model\PlatformAccount;

/**
* Class SmashcastAccount.
* Gets and manage account from Smashcast, interact with database
*/
class SmashcastAccount extends PlatformAccount
{
    /**
    * Gets token from Smashcast
    * @param array $data Data to pass to the streaming platform API (app token, secret token...)
    */
    public function getTokenFromSource(Array $data)
    {	
		$url = $this->platform->getApiUrl('get_token');
		$http_header = array('Client-ID: '.$this->platform->getApiKeys()['app_token']);

        $response = $this->curlRequest($url, $data, $http_header);
        $json_result = json_decode($response, true);
        $this->token = $json_result["access_token"];
    }

    /**
    * Gets username from Smashcast
    */
    public function getUsernameFromSource()
    {
		$url = $this->platform->getApiUrl('get_username', ['token_val' => $this->token]);
        $response = $this->curlRequest($url, null ,null);
        $decode_response = json_decode($response, true);
        if(isset($decode_response["user_name"]))
        {
            $this->username = $decode_response["user_name"];
        }		
    }

    /**
    * Gets profile picture from Smashcast
    */
    public function getProfilePictureFromSource()
    {
		$response = $this->curlRequest($this->platform->getApiUrl('get_profile_pic', ['username_val' => $this->username]), null, array('Authorization: OAuth '.$this->token));
		$decode_response = json_decode($response, true);
		if(isset($decode_response["user_logo"]))
		{
			$this->profil_picture_url = 'https://edge.sf.hitbox.tv'.$decode_response["user_logo"];
		}	
    }

    /**
    * Saves Smashcast user informations into database
    * @param PDO $db Database connection object
    * @param string $username Streaming platform username
    * @param int $user_id The Vigas user id
	* @return array|false Returns Smashcast username and token if informations have been saved, false otherwise
    */
    public function saveToDB(\PDO $db, $username, $user_id)
    {	
        $req = $db->prepare('SELECT count(id) as nb_id FROM SmashcastAccount WHERE user_id=:user_id');
        $req->execute(array(
            'user_id' => $user_id
        ));
        $resultat = $req->fetch();
        if($resultat['nb_id']==0)
        {
            $req = $db->prepare('INSERT INTO SmashcastAccount (user_id, smashcast_username, smashcast_token) VALUES(:user_id, :username, :encrypted_token)');
            $encrypted_token = $this->cryptToken($this->token);
            $resultat=$req->execute(array(
                'user_id' => $user_id,
                'username' => $username,
                'encrypted_token' => base64_encode($encrypted_token)
            ));
        }
        else
        {
            $req = $db->prepare('UPDATE SmashcastAccount SET smashcast_username=:username, smashcast_token=:encrypted_token WHERE user_id=:user_id');
            $encrypted_token = $this->cryptToken($this->token);
            $resultat = $req->execute(array(
                'username' => $username,
                'encrypted_token' => base64_encode($encrypted_token),
                'user_id' => $user_id
            ));
        }

        if ($resultat)
        {
            return array('smashcast_username' => $username, 'smashcast_token' => $this->decryptToken($this->token));
        }
        else
        {
            return false;
        }
    }

    /**
    * Gets Smashcast user informations from the database
    * @param PDO $db Database connection object
    * @param int $user_id The Vigas user id
	* @return object The Smashcast account
    */
    public function getFromDB(\PDO $db, $user_id)
    {
        $req = $db->prepare('SELECT smashcast_username, smashcast_token FROM SmashcastAccount WHERE user_id=:user_id');
        $req->execute(array(
                'user_id' => $user_id
        ));
        $resultat = $req->fetch();
        if(isset($resultat['smashcast_username']) && isset($resultat['smashcast_token']))
        {
            $this->username = $resultat['smashcast_username'];
            $this->token = base64_decode($resultat['smashcast_token']);
            $this->getProfilePictureFromSource();
            return $this;
        }
    }
}
