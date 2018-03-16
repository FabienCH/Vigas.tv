<?php 
namespace Vigas\Application\Controller;
use \Vigas\Application\Application;

/**
 * Trait Encryption.
 * Crypt and decrypt a token
 */
trait Encryption
{
    /**
    * Crypt a token
	* @param string $token token to crypt
    * @return string the crypted token
    */
    public function cryptToken($token)
    {
        $pub_key_path = Application::getConfigFromXML('Application/config.xml', 'encryption');
		var_dump($pub_key_path);
		$file_pub_key = fopen($pub_key_path['public_key_path'],"r");
		$public_key = fread($file_pub_key,2048);
		fclose($file_pub_key);
		openssl_pkey_get_public($public_key);
		openssl_public_encrypt($token, $crypted_token, $public_key);
		return $crypted_token;
    }

    /**
    * Decrypt a token
    * @param string $encrypted_token the crypted token
    * @return string the decrypted token
    */
    public function decryptToken($encrypted_token)
    {
        $priv_key_path = Application::getConfigFromXML('Application/config.xml', 'encryption');
		var_dump($priv_key_path);
		$file_priv_key = fopen($priv_key_path['private_key_path'],"r");
		$private_key = fread($file_priv_key,2048);
		fclose($file_priv_key);
		openssl_pkey_get_private($private_key);
		openssl_private_decrypt($encrypted_token, $token, $private_key);
        return $token;
    }
	
}
