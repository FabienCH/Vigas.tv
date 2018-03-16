<?php
namespace Vigas\Application\Controller;

use Vigas\Application\View\View;
use Vigas\Application\Application;
use Vigas\Application\Controller\Mailer;
use Vigas\Application\Controller\Captcha;
use Vigas\Application\Model\UserManager;

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
		var_dump(Application::getPath());
		if(!is_null($http_request))
		{
			if(isset($http_request->getGetData()['action']) && in_array($http_request->getGetData()['action'], Application::getPath()))
			{
				if(!empty($http_request->getPostData()))
				{
					$this->post_params = $http_request->getPostData();
				}
			    $this->navbar_method_name = 'getDefaultNavbar';
				$this->method_name = $this->setMethodName($http_request->getGetData()['action']);
			}
		}
    }
	
	/**
    * Sets the method name the controller will use
    * @param string $action the action url parameter
    * @return string the method name
    */
    public function setMethodName($action)
	{
        if(strpos($action, '-'))
        {
            $array = explode('-', $action);
            $action = 'get';
            foreach ($array as &$word)
            {
                $action .= ucfirst($word);
            }
            return $action;
        }
        else
        {
            return 'get'.ucfirst($action);
        }        
    }
	
	/**
    * Executes AppController methods to get content and navbar
    */
    public function executeController()
    {
		$ctrl_method_name = $this->method_name;
        $this->$ctrl_method_name();
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
    public function getLogin()
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
    * @param array $this->post_params HTTP POST parameters
    */
    public function getResetPassword()
    {
		$http_request = Application::getHTTPRequest();
        if(isset($http_request->getGetData()['token']) && isset($http_request->getGetData()['id']))
        {
            $user_manager = new UserManager;
            $this->response['token_validity'] = $user_manager->testTokenValidity($http_request->getGetData()['id'], $http_request->getGetData()['token']);
            if(isset($this->post_params['set-password']) && $this->response['token_validity'])
            {
                $this->response['reset_password_error'] = $user_manager->resetPassword($http_request->getGetData()['id'], $http_request->getGetData()['token'], $this->post_params['password'], $this->post_params['password-2']);
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
