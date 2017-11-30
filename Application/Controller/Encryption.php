<?php 
namespace Vigas\Application\Controller;

/**
 * Trait Encryption.
 * Crypt and decrypt a token
 */
trait Encryption
{
    /**
    * Crypt a token
	* @param string $token token to crypt
	* @param string $key key used to crypt the token
    * @return string the crypted token
    */
    public function cryptToken($token, $key)
    {
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $crypted_token = base64_encode(mcrypt_generic($td, $token));
        mcrypt_generic_deinit($td);

        return $crypted_token;
    }

    /**
    * Decrypt a token
    * @param string $encrypted_token the crypted token
    * @param string $key key used to crypt the token
    * @return string the decrypted token
    */
    public function decryptToken($encrypted_token, $key)
    {
        $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $decrypted_token = mdecrypt_generic($td, base64_decode($encrypted_token));
        mcrypt_generic_deinit($td);

        $exploded_token = explode(':',$decrypted_token);
        $token = $exploded_token[2];
        $token = substr($token,1,$exploded_token[1]);	
        return $token;
    }
	
}
