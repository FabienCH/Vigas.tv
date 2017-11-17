<?php
namespace Vigas\Application\Controller;

use Vigas\Application\View\View;
use Vigas\Application\Application;
use Vigas\Application\Controller\Mailer;
use Vigas\Application\Controller\Captcha;
use Vigas\Model\LinkedAccount;

/**
* Class AppController
* The application controller
*/
class AppController
{
	/**
    * @var string navbar_method_name contains name of the AppController method used to get the navbar
    */
    protected $navbar_method_name;
	
	/**
    * @var string method_name contains name of the AppController method used to get the content
    */
	protected $method_name;
    
	/**
    * @var array $response data to be send to the view
    */
    protected $response = [];
	
	/**
    * @var array post_params parameters sent via POST method
    */
    protected $post_params;
	
	/**
    * Sets parameters for the model and methods name
    */
    public function __construct()
    {
		$http_request = Application::getHTTPRequest();
		if(!is_null($http_request))
		{
			if(isset($http_request->getGetData()['action']) && $http_request->getGetData()['action'] == 'about')
			{
				if(!empty($http_request->getPostData()))
				{
					$this->post_params = $http_request->getPostData();
				}
			    $this->navbar_method_name = 'getDefaultNavbar';
				$this->method_name = 'getAbout';
			}
		}
    }
	
	/**
    * Executes AppController methods to get content and navbar
    */
    public function executeController()
    {
		$ctrl_method_name = $this->method_name;
		//$navbar_method_name = $this->navbar_method_name;
        $this->$ctrl_method_name();
		//$this->$navbar_method_name();
    }
	
	/**
    * Executes AppController methods to get content and navbar
	* Creates the view and call View method and template
    */
	public function getView()
    {	
		$view = new View($this->post_params, $this->response);
		$view_method_name = $this->method_name.'View';
		$view->$view_method_name();
		$view->getTemplate();
    }
	
    /**
    * Get data for the linked accounts view
    */
    public function getLinkedAccount()
    {
        if(isset($this->post_params['login']))
        {
            $user_manager = new UserManager;
            $this->response['login_error'] = $user_manager->logUser($this->post_params['log-username'], $this->post_params['log-password'], $this->post_params['log-remember-me']);
        }

        if(isset($this->post_params['create-account']))
        {
            $user_manager = new UserManager;
            $this->response['create_account_error'] = $user_manager->createAccount($this->post_params['ca-username'], $this->post_params['ca-email'], $this->post_params['ca-password'], $this->post_params['ca-password-2'], $this->post_params['ca-remember-me']);
        }
        
        if(isset($this->post_params['first-link-done']))
        {
            $user_manager = new UserManager;
            $this->response['first_link_error'] = $user_manager->setFirstLinkDone();
        }
    }
    
    /**
    * Save streaming platforms token once identified
    * @param array $get_params HTTP GET parameters
    * @param array $this->post_params HTTP POST parameters
    */
    public function getSaveToken($get_params)
    {
        if(Application::getUser() !== null)
        {
            if(isset($get_params['code']))
            {
                $linked_account = new LinkedAccount('twitch');

                $data = array('client_id' => Application::TWITCH_APP['client_id'], 'client_secret' => Application::TWITCH_APP['client_secret'], 'grant_type' => 'authorization_code', 'redirect_uri' => 'https://vigas.tv'.Application::BASE_URL.'save-token', 'code' => $get_params['code'], 'state' => 'oauth2');

                $linked_account->getTokenFromSource($data);
                $linked_account->getUsernameFromSource();	
                $linked_account->saveToDB(Application::getPDOconnection(), $linked_account->getUsername(), Application::getUser()->getId());
                $linked_account->getProfilePictureFromSource();
            }

            if(isset($get_params['request_token']))
            {
                $linked_account = new LinkedAccount('smashcast');

                $data = array('request_token' => $get_params['request_token'], 'app_token' => SMASHCAST_APP['app_token'], 'hash' => base64_encode(SMASHCAST_APP['app_token'].SMASHCAST_APP['app_secret']));

                $linked_account->getTokenFromSource($data);
                $linked_account->getUsernameFromSource();	
                $linked_account->saveToDB(Application::getPDOconnection(), $linked_account->getUsername(), Application::getUser()->getId());
                $linked_account->getProfilePictureFromSource();
            }

            if(isset($get_params['authToken']))
            {
                $linked_account = new LinkedAccount('smashcast');

                $linked_account->setToken( $get_params['authToken']);	
                $linked_account->getUsernameFromSource();
                $linked_account->saveToDB(Application::getPDOconnection(), $linked_account->getUsername(), Application::getUser()->getId());
                $linked_account->getProfilePictureFromSource();
            }
        }
		
        $user_manager = new UserManager;
        $_SESSION['linked_accounts'] = serialize($user_manager->getAllLinkedAccounts(Application::getPDOconnection(), Application::getUser()));
        if(Application::getUser() !== null && Application::getUser()->getFirstLinkDone()==0)
        
        {
            header('Location: https://vigas.tv'.Application::BASE_URL.'following');
        }
        else
        {
            header('Location: https://vigas.tv'.Application::BASE_URL.'linked-account');
        }         
    }
    
    /**
    * Logout user
    */
    public function getLogOut()
    {
       $user_manager = new UserManager;
       $user_manager->logOut();   
    }
    
    /**
    * Get data for the user profile view
    * @param array $this->post_params HTTP POST parameters
    */
    public function getProfile()
    {
        if(isset($this->post_params['change-password']))
        {
            $user_manager = new UserManager;
            $this->response['change_pwd_error'] = $user_manager->changePassword(Application::getUser(), $this->post_params['current-password'], $this->post_params['new-password'], $this->post_params['new-password-2']);
        }
        
        if(isset($this->post_params['login']))
        {
            $user_manager = new UserManager;
            $this->response['change_pwd_error'] = $user_manager->logUser($this->post_params['log-username'], $this->post_params['log-password'], $this->post_params['log-remember-me']);
        }

        if(isset($this->post_params['create-account']))
        {
            $user_manager = new UserManager;
            $this->response['create_account_error'] = $user_manager->createAccount($this->post_params['ca-username'], $this->post_params['ca-email'], $this->post_params['ca-password'], $this->post_params['ca-password-2'], $this->post_params['ca-remember-me']);
        }
    }
    
    /**
    * Get data for the forgot password view
    * @param array $this->post_params HTTP POST parameters
    */
    public function getForgotPassword()
    {
        $user_manager = new UserManager;
        if(isset($this->post_params['reset-password']))
        {
            $this->response['forgot_password_error'] = $user_manager->sendResetPwdEmail($this->post_params['email']);
        }
        elseif(isset($this->post_params['find-email']))
        {
            $this->response['find_email'] = $user_manager->findEmail($this->post_params['username']);
        }
    }
    
    /**
    * Get data for the reset password view
    * @param array $get_params HTTP GET parameters
    * @param array $this->post_params HTTP POST parameters
    */
    public function getResetPassword($get_params)
    {
        if(isset($get_params['token']) && isset($get_params['id']))
        {
            $user_manager = new UserManager;
            $this->response['token_validity'] = $user_manager->testTokenValidity($get_params['id'], $get_params['token']);
            if(isset($this->post_params['set-password']) && $this->response['token_validity'])
            {
                $this->response['reset_password_error'] = $user_manager->resetPassword($get_params['id'], $get_params['token'], $this->post_params['password'], $this->post_params['password-2']);
            }   
        }
    }
    
    /**
    * Get data for the about view
    * @param array $this->post_params HTTP POST parameters
    */
    public function getAbout()
    {
        if(isset($this->post_params["message-type"]) && $this->post_params["message-type"]=="bug report")
        {
            $this->response['selected'] = "selected";
        }
        else
        {
            $this->response['selected'] = "";
        }
            
        if(isset($this->post_params["message-type"]))
        {
            $this->response['status'] = false;
            $body = "";
			$captcha_config = Application\Application::getConfigFromXML(__DIR__.'/../config.xml', 'captcha');
            $captcha = new Captcha($captcha_config['siteKey'], $captcha_config['secretKey'], $this->post_params['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);	

            if(isset($this->post_params["url"]))
            {
                if($this->post_params["url"] == "" || $this->post_params["message"] == "")
                {
                    $this->response['message'] = '<div class="alert alert-warning">All fileds are required</div>';	
                }
                else
                {
                    $body = "URL : ".$this->post_params["url"]."\n".$this->post_params["message"];
                }
            }
            else
            {
                if($this->post_params["message"] == "")
                {
                    $this->response['message'] = '<div class="alert alert-warning">Please write a message</div>';
                }
                else
                {
                    $body = htmlspecialchars($this->post_params["message"]);
                }
            }

            if(!isset($this->post_params["email"]) || $this->post_params["email"] == "")
            {
                    $from = 'anonymous@vigas.tv';
                    $from_name = 'Anonymous User';
            }
            else
            {
                if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,20}$#", $this->post_params['email']))
                {
                    $this->response['message'] = '<div class="alert alert-warning">Invalid email address format</div>';
                }
                else
                {
                    $from = $this->post_params["email"];
                    $from_name = $this->post_params["email"];
                }	
            }

            if(!isset($this->response['message']))
            {
                if($captcha->validCaptcha())
                {
					$smtp_config = Application\Application::getConfigFromXML(__DIR__.'/../config.xml', 'smtp');
                    $mail = new Mailer($from, $from_name, 'auth.smtp.1and1.fr', $this->post_params["message-type"].' from Vigas', $body,  'admin@vigas.tv', $smtp_config); 
                    if($mail->sendMail())
                    {
                        $this->response['message'] = '<div class="alert alert-success">Your '.$this->post_params["message-type"].' has been sent. Thank you !</div>';
                    }
                    else
                    {
                        $this->response['message'] = $this->response['status'];
                    }
                    $mail->SmtpClose(); 
                    unset($mail);
                }
                else
                {
                    $this->response['message'] = '<div class="alert alert-warning">Please valid the captcha</div>';
                }
            }                
        }
    }
    
    /**
    * Manage the update info alert
    * @param array $get_params HTTP GET parameters
    */
    public function getManageUpdateInfo($get_params)
    {
        if($get_params['do']=='close-update')
        {
            session_start();
            $_SESSION['dont-show-update']=1;
        }
        if($get_params['do']=='dont-show-anymore')
        {
            setcookie('dont-show-update', 1, time() + 365*24*3600, '/', null, false, true);
        }
    }
    
    /**
    * Get the gif to display in 404 eror page
    */
    public function get404()
    {
         $this->response['file_path'] = Application::BASE_URL."view/img/gif-404/".mt_rand(1,27).".gif";
    }
    
    /**
    * @return array response for the view
    */
    public function getResponse()
    {
        return $this->response;
    }
}

