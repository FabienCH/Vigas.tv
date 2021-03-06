<?php
namespace Vigas\Application\Model;

use Vigas\Application\Application;
use Vigas\Application\Model\User;
use Vigas\Application\Controller\FormValidator;
use Vigas\Application\Controller\Mailer;
use Vigas\StreamingPlatforms\Model\Twitch;
use Vigas\StreamingPlatforms\Model\Smashcast;
use Vigas\StreamingPlatforms\Model\TwitchAccount;
use Vigas\StreamingPlatforms\Model\SmashcastAccount;

/**
* Class UserManager.
* Manages an authenticated user account
*/
class UserManager
{
	 /**
    * @var User An authenticated user
    */
    private $user;
	
	 /**
    * @var \PDO Database connection object
    */
    private $db;
    
	/**
    * Sets user and db attributes
    */
    public function __construct()
    {
        if(Application::getUser() !== null)
        {		
            $this->user = Application::getUser();
        }
        $this->db = Application::getPDOconnection();
    }

	/**
    * Validates given data to create a user account and calls insertUser method
    * @param string $username The user username
    * @param string $email The user email address
    * @param string $password The user password
    * @param string $password2 The user password (confirm your password field)
    * @param string|null $remember_me The remember me checkbox
    * @return string The error message if some data were not validated
    */
    public function createAccount($username, $email, $password, $password2, $remember_me = null)
    {
        $validator = new FormValidator;
        if(!$validator->checkAlphanum($username) || !$validator->checkLength($username, 3, 40))
        {
            return '<div class="alert alert-warning">Username must be alphanumeric and between 3 and 40 characters</div>';
        }
        if(!$validator->checkEmail($email))
        {
            return '<div class="alert alert-warning">Invalid email address format</div>';
        }
        if($password != $password2)
        {
            return '<div class="alert alert-warning">Passwords do not match</div>';;
        }
        if(!$validator->checkLength($password, 6, 255))
        {
            return '<div class="alert alert-warning">Password must contain at least 6 characters</div>';
        }
        
        $this->user = new User;
        if(!$this->user->checkUniqueUsername($this->db, $username))
        {
            return '<div class="alert alert-warning">Username not available</div>';
        }
        elseif(!$this->user->checkUniqueEmail($this->db, $email))
        {
            return '<div class="alert alert-warning">This email is already used. <a href="'.Application::getBaseURL().'forgot-password">Forgot username or password ?</a></div>';
        }
        else
        {         
            $account_created = $this->user->insertUser($this->db, $username, $email, $password);
            if($account_created)
            {
                $this->logUser($username, $password, $remember_me);
            }
        }
    }
    
	/**
    * Logs a user, gets his streaming platform accounts and redirect him to the following page
    * @param string $username The user username
    * @param string $password The user password
    * @param string|null $remember_me The remember me checkbox
    * @return string The error message if the user or password are wrong
    */
    public function logUser($username, $password, $remember_me = null)
    {
        $this->user = new User;
        $test_user = $this->user->getUser($this->db, ['username' => $username], $password);
        if($test_user !== false)
        {
            if($remember_me)
            {
                setcookie('user', serialize($this->user), time() + 365*24*3600, '/', null, false, true);
            }
            else
            {
                $_SESSION['user'] = serialize($this->user);
            }
            $_SESSION['platform_accounts'] = serialize($this->getPlatformAccountsFromDB($this->db, $this->user));
			$this->user->logUserLogin(Application::getPDOconnection());	
            header('Location: https://vigas.tv'.Application::getBaseURL().'following');
        }
        else
        {
            return '<div class="alert alert-warning">Wrong username or password</div>';
            $GLOBALS["login_form_data"] = $_POST['username'];
        }      
    }
    
	/**
    * Validates given data to change a user paswword and calls updatePassword method
    * @param string $current_password The user current password
    * @param string $new_password The new user password
    * @param string $new_password_2 The new user password (confirm your password field)
    * @return string The error message if some data were not validated
    */
    public function changePassword($current_password, $new_password, $new_password_2)
    {
        $validator = new FormValidator;
        if($new_password != $new_password_2)
        {
            return '<div class="alert alert-warning">New passwords do not match</div>';;
        }
        elseif(!$validator->checkLength($new_password, 6, 255))
        {
            return '<div class="alert alert-warning">New Password must contain at least 6 characters</div>';
        }
        else
        {
            $test_user = $this->user->getUser($this->db, ['username' => $this->user->getUsername()], $current_password);
            if($test_user !== false)
            {
                $password_changed = $this->user->updatePassword($this->db, ['id' => $this->user->getId(), 'username' => $this->user->getUsername()], $new_password);
                if($password_changed)
                {
                    return '<div class="alert alert-success">Your password has been changed</div>';
                }
                else
                {
                    return '<div class="alert alert-warning">Something went wrong, please try again</div>';
                }
            }
            else
            {

                return '<div class="alert alert-warning">Incorrect current password</div>';
            }
        }
        
    }
    
	/**
    * Tests if the reset password token is still valid (not expired)
    * @param int $id The user id
    * @param string $token The reset password token
    * @return boolean Returns true if the token is valid, false otherwise
    */
    public function testTokenValidity($id, $token)
    {
        $this->user = new User;
        $test_user = $this->user->getUser($this->db, ['id' => $id, 'reset_pwd_token' => $token]);
        if($test_user !== false)
        {
            $token_endat = date('Y-m-d H:i:s', strtotime($this->user->getResetPwdTokenEndat()));
            if($token_endat > date("Y-m-d H:i:s") && $token == $this->user->getResetPwdToken())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
	/**
    * Validates given data to reset a user paswword and calls updatePassword method
    * @param int $id The user id
    * @param string $token The reset password token
	* @param string $new_password The new user password
    * @param string $new_password_2 The new user password (confirm your password field)
    * @return string The error message if some data were not validated
    */
    public function resetPassword($id, $token, $new_password, $new_password_2)
    {
        $validator = new FormValidator;
        if($new_password != $new_password_2)
        {
            return '<div class="alert alert-warning">New passwords do not match</div>';;
        }
        elseif(!$validator->checkLength($new_password, 6, 255))
        {
            return '<div class="alert alert-warning">New Password must contain at least 6 characters</div>';
        }
        else
        {
            $this->user = new User;
            $password_changed = $this->user->updatePassword($this->db, ['id' => $id, 'reset_pwd_token' => $token], $new_password);
            $delete_token = $this->user->deleteResetPwdToken($this->db, $id);
            if($password_changed && $delete_token)
            {  
                return '<div class="alert alert-success">Your password has been changed. Please log in <a href="'.Application::getBaseURL().'following">here</a></div>';
            }
            else
            {
                return '<div class="alert alert-warning">Something went wrong, please try again</div>';
            }
        }     
    }
    
	/**
    * Sends an email with a reset password link
    * @param string $email The user email address
    * @return string Confirmation message that the email has been sent or an error message
    */
    public function sendResetPwdEmail($email)
    {
        $this->user = new User();
        $test_user = $this->user->getUser($this->db, ['email' => $email]);
        if($test_user !== false)
        {
            $reset_pwd_token = bin2hex(random_bytes(20));
            if($this->user->saveResetPwdToken($this->db, $reset_pwd_token, $this->user->getId()))
            {               
                $body='<body><a href="https://vigas.tv'.Application::getBaseURL().'"><img style="height:100%;" alt="vigas email banner" src="https://vigas.tv/View/img/email-banner.jpg" /></a><h2>Vigas.tv : reset password</h2><p>'.$this->user->getUsername().',</p><p>In order to reset your passord, please click (or copy/paste) the following link (this link is only available for 30 minutes) :<br/><a href="https://vigas.tv'.Application::getBaseURL().'reset-password/token='.$reset_pwd_token.'&id='.$this->user->getId().'">https://vigas.tv'.Application::getBaseURL().'reset-password/token='.$reset_pwd_token.'&id='.$this->user->getId().'</a></p><p><strong>IMPORTANT : Do not click this link if you did not request a password reset. Note that I\'ll never ask you for your password, do not answer to any email who would.</strong></p><p>Regards,<br/>Admin@Vigas.tv</p></body>';

                $mail = new Mailer('admin@vigas.tv', 'Vigas Admin', 'auth.smtp.1and1.fr', 'Reset your Vigas password', $body,  $_POST['email'], Application::getSMTPConf());
                $mail->IsHTML(true);
                if(!$mail->Send())
                {
                    $this->user->deleteResetPwdToken($this->db, $this->user->getId());
                    return '<div class="alert alert-warning">Couldn\'t send email. Email address '.$email.' is probably incorrect </div>';
                }
                else
                {
                    return 'success';
                }
            }
            else
            {	
                return '<div class="alert alert-warning">Something went wrong, please try again</div>';
            }
        }
        else
        {	
            return '<div class="alert alert-warning">No account found with email '.$email.'</div>';
        }
    }
    
	/**
    * Finds a user email address with a given username
    * @param string $username The user username
    * @return string The user email address or a message saying that the username has not been found 
    */
    public function findEmail($username)
    {
        $this->user = new User();
        $test_user = $this->user->getUser($this->db, ['username' => $username]);
        if($test_user !== false)
        {
            $exploded_email = explode('@',$this->user->getEmail());
            $nb_char_email = strlen($exploded_email[0]);
            if($nb_char_email<=8)
            {
                $exploded_email[0] = substr_replace($exploded_email[0], '****', 2, $nb_char_email-2);
            }
            else
            {
                $exploded_email[0] = substr_replace($exploded_email[0], '***', 2, $nb_char_email-4);
            }
            $response['email_found'] = '<div class="alert alert-info">Your email address : '.$exploded_email[0].'@'.$exploded_email[1].'</div>';
        }
        else
        {
                $response['username_not_found'] =  '<div class="alert alert-warning">Couldn\'t find username '.$username.'</div>';
        }
        return $response;
    }
    
	/**
    * Gets the user's streaming platform accounts from the database
    */
    public function getPlatformAccountsFromDB()
    {
		$twitch = new Twitch;
        $twitch_account = new TwitchAccount($twitch);
		$twitch_account->getFromDB($this->db, $this->user->getId());
		if(!is_null($twitch_account->getUsername()) && !is_null($twitch_account->getToken()))
		{
			$this->user->addPlatformAccounts($twitch_account);
		}	
		
		$smashcast = new Smashcast;
		$smashcast_account = new SmashcastAccount($smashcast);
		$smashcast_account->getFromDB($this->db, $this->user->getId());
		if(!is_null($smashcast_account->getUsername()) && !is_null($smashcast_account->getToken()))
		{
			$this->user->addPlatformAccounts($smashcast_account);
		}
		
    }
    
	/**
    * Calls the firstLinkDone method and redirect the user to the following page
    * @param string $username The user username
    * @return string An error message if the firstLinkDone method returned false
    */
    public function setFirstLinkDone()
    {
        $first_link_done_set = $this->user->firstLinkDone($this->db, $this->user->getId());

        if(!$first_link_done_set)
        {
            return '<div class="alert alert-warning">Something went wrong, please try again</div>';
        }
        else
        {
            if(isset($_COOKIE['user']))
            {
                setcookie('user', serialize($this->user), time() + 365*24*3600, '/', null, false, true);
            }
            if(isset($_SESSION['user']))
            {
                $_SESSION['user'] = serialize($this->user);
            }
            header('Location: https://vigas.tv'.Application::getBaseURL().'following');
        }
    }

	/**
    * Destroys user cookie and session and redirect the user to the main page
    */
    public function logOut()
    {
        setcookie('user', '', time() - 3600, '/', null, false, true);

        if(isset($_COOKIE[session_name()])):
                setcookie(session_name(), '', time() - 3600 , '/');
            endif;

        session_unset();
        session_destroy();

        header('Location: https://vigas.tv'.Application::getBaseURL());      
    }

}
