<?php
namespace Vigas\StreamingPlatforms\Model;

use Vigas\Application\Model\CurlRequest;
use Vigas\Application\Controller\Encryption;
use Vigas\Application\Application;
use Vigas\StreamingPlatforms\Model\PlatformAccount;

/**
* Class TwitchAccount
* Get and manage account from Twitch, interact with database
*/
class TwitchAccount extends PlatformAccount
{  
    /**
    * Get token from Twitch
    * @param array $data data to pass to the streaming platform API (app token, secret token...)
    */
    public function getTokenFromSource(Array $data)
    {	            
		$url = $this->platform->getApiUrl('get_token');
		$http_header = array('Client-ID: '.$this->platform->getApiKeys()['client_id']);

        $response = $this->curlRequest($url, $data, $http_header);
        $json_result = json_decode($response, true);
        $this->token = $json_result["access_token"];
		var_dump($json_result);
    }

    /**
    * Get username from Twitch
    */
    public function getUsernameFromSource()
    {
		$url = $this->platform->getApiUrl('get_username');
		$http_header = array('Client-ID: '.$this->platform->getApiKeys()['client_id'], 'Authorization: OAuth '.$this->token);

        $response = $this->curlRequest($url, null ,$http_header);
        $decode_response = json_decode($response, true);
        if(isset($decode_response["name"]))
        {
            $this->username = $decode_response["name"];
        }  
    }

    /**
    * Get profile picture from Twitch
    */
    public function getProfilePictureFromSource()
    {
		$response = $this->curlRequest($this->platform->getApiUrl('get_username'), ['username_val' => $this->username], null, array('Authorization: OAuth '.$this->token));
		$decode_response= json_decode($response, true);
		if(isset($decode_response["logo"]))
		{
			$this->profil_picture_url = $decode_response["logo"];
		}
		else
		{
			$this->profil_picture_url = 'https://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_150x150.png';
		}   
    }

    /**
    * Save Twitch user informations into database
    * @param object PDO $db database connection object
    * @param string $username streaming platform username
    * @param int $user_id the "local" user id
    */
    public function saveToDB(\PDO $db, $username, $user_id)
    {	
        $req = $db->prepare('SELECT count(id) as nb_id FROM TwitchAccount WHERE user_id=:user_id');
        $req->execute(array(
            'user_id' => $user_id
        ));
        $resultat = $req->fetch();
        if($resultat['nb_id']==0)
        {
            $req = $db->prepare('INSERT INTO TwitchAccount (user_id, twitch_username, twitch_token) VALUES(:user_id, :username, :encrypted_token)');
            $encrypted_token = $this->cryptToken($this->token);
            $resultat = $req->execute(array(
                'user_id' => $user_id,
                'username' => $username,
                'encrypted_token' => base64_encode($encrypted_token)
            ));
        }
        else
        {
            $req = $db->prepare('UPDATE TwitchAccount SET twitch_username=:username, twitch_token=:encrypted_token WHERE user_id=:user_id');
            $encrypted_token = $this->cryptToken($this->token);
            $resultat = $req->execute(array(
                'username' => $username,
                'encrypted_token' => base64_encode($encrypted_token),
                'user_id' => $user_id
            ));
        }

        if ($resultat)
        {
            return array('twitch_username' => $username, 'twitch_token' => $this->decryptToken($this->token));
        }
        else
        {
            return false;
        }
    }

    /**
    * Get Twitch user informations from the database
    * @param object PDO $db database connection object
    * @param int $user_id the "local" user id
    */
    public function getFromDB(\PDO $db, $user_id)
    {
        $req = $db->prepare('SELECT twitch_username, twitch_token FROM TwitchAccount WHERE user_id=:user_id');
        $req->execute(array(
                'user_id' => $user_id
        ));
        $resultat = $req->fetch();
        if(isset($resultat['twitch_username']) && isset($resultat['twitch_token']))
        {
            $this->username = $resultat['twitch_username'];
            $this->token = $this->decryptToken(base64_decode($resultat['twitch_token']));
            $this->getProfilePictureFromSource();
            return $this;
        }
    }
}
