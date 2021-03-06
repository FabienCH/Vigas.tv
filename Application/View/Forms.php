<?php
namespace Vigas\Application\View;

use Vigas\Application\Controller\FormBuilder;
use Vigas\Application\Model\User;

/**
* Class Form.
* Manages an authenticated user account
*/
abstract class Forms
{
	/**
    * Builds the login form and displays it
    * @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    * @param string $username Previously entered username if the form has already been sent
    */
    public static function getLoginForm($target_url, $method, $username)
    {
        $form = new FormBuilder($target_url, $method);
        $form->addInputHTML('log-username', 'Username', 'text', 'log-username', $username);
        $form->addInputHTML('log-password', 'Password', 'password', 'log-password');
        $form->addOneCheckboxHTML('log-remember-me', 'Remember me', 'checkbox', 'log-remember-me', 'checkbox-inline', 'checked');
        $form->addSubmitButton('Sign In', 'login', 'btn btn-primary');
        echo $form;
    }
    
	/**
    * Builds the create account form and displays it
    * @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    * @param array $value_array Previously entered data if the form has already been sent
    */
    public static function getCreateAccountForm($target_url, $method, $value_array)
    {
        isset($value_array['ca-email']) ? $email = $value_array['ca-email'] : $email = '';
        isset($value_array['ca-username']) ? $username = $value_array['ca-username'] : $username = '';
        $form = new FormBuilder($target_url, $method);
        $form->addInputHTML('ca-email', 'Email address', 'email', 'ca-email', $email);
        $form->addInputHTML('ca-username', 'Username', 'text', 'ca-username', $username);
        $form->addInputHTML('ca-password', 'Password', 'password', 'ca-password');
        $form->addInputHTML('ca-password-2', 'Confirm Password', 'password', 'ca-password-2');
        $form->addOneCheckboxHTML('ca-remember-me', 'Remember me', 'checkbox', 'ca-remember-me', 'checkbox-inline', 'checked');
        $form->addSubmitButton('Create Account', 'create-account', 'btn btn-primary');
        echo $form;
    }
    
	/**
    * Builds the forgot password form and displays it
    * @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    * @param string $email Previously entered email if the form has already been sent
    */
    public static function getForgotPasswordForm($target_url, $method, $email)
    {
        $form = new FormBuilder($target_url, $method);
        $form->addInputHTML('email', 'Email address', 'email', 'email', $email);
        $form->addSubmitButton('Reset Password', 'reset-password', 'btn btn-primary');
        echo $form;
    }
    
	/**
    * Builds the reset password form and displays it
    * @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    */
    public static function getResetPasswordForm($target_url, $method)
    {
        $form = new FormBuilder($target_url, $method);
        $form->addInputHTML('password', 'Password', 'password', 'password');
        $form->addInputHTML('password-2', 'Confirm Password', 'password', 'password-2');
        $form->addSubmitButton('Set Password', 'set-password', 'btn btn-primary');
        echo $form;
    }
    
	/**
    * Builds the forgot password form and displays it
    * @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    * @param string $username Previously entered username if the form has already been sent
    */
    public static function getFindEmailForm($target_url, $method, $username)
    {
        $form = new FormBuilder($target_url, $method);
        $form->addInputHTML('username', 'Username', 'text', 'username', $username);
        $form->addSubmitButton('Find Email', 'find-email', 'btn btn-primary');
        echo $form;
    }
    
	/**
    * Builds the change password form and displays it
    * @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    * @param User $user The authenticated user to change the password to
    */
    public static function getChangePwdForm($target_url, $method, User $user)
    {
        $form = new FormBuilder($target_url, $method);
        $form->addInputHTML('username', 'Username', 'text', 'username', ucfirst($user->getUsername()), 'disabled');
        $form->addInputHTML('email', 'Email address', 'email', 'email', $user->getEmail(), 'disabled'); 
        $form->addInputHTML('current-password', 'Current password', 'password', 'current-password');
        $form->addInputHTML('new-password', 'New Password', 'password', 'new-password');
        $form->addInputHTML('new-password-2', 'Confirm New Password', 'password', 'new-password-2');
        $form->addSubmitButton('Change Password', 'change-password', 'btn btn-primary');
        echo $form;
    }
    
	/**
    * Builds the contact form and displays it
    * @param string $target_url Page's URL that handles the form data
    * @param string $method The HTTP method to be used when submitting the form (GET or POST)
    * @param array $value_array Previously entered data if the form has already been sent
    */
    public static function getContactForm($target_url, $method, $value_array)
    {
        isset($value_array['email']) ? $email = $value_array['email'] : $email = '';
        isset($value_array['message']) ? $message = $value_array['message'] : $message = '';
        isset($value_array['message-type']) ? $selected = $value_array['message-type'] : $selected = '';
        isset($value_array['url']) ? $url = $value_array['url'] : $url = '';
        
        $form = new FormBuilder($target_url, $method);
        $form->addSelectHTML('message-type', '', 'message-type', ['Feedback', 'Bug Report'], $selected);
        $form->addInputHTML('email', 'Your email adress (fill it if you expect a reply)', 'email', 'email', $email);
        
        if(isset($value_array['message-type']) && $value_array['message-type'] == 'Bug Report')
        {
            $form->addTextHTML('<p class="alert alert-info">Please provide as much details as you can on the bug (which page you were, what you were doing when the bug appeared, any error message you could have...)</p>');
            $form->addInputHTML('url', 'Webpage\'s URL where the bug appeared', 'text', 'url', $url);
        }
        
        $form->addTextareaHTML('message', 'Message', 'textarea', 'message', 10, $message);
        $form->addCaptcha();
        $form->addSubmitButton('Submit', 'about-form', 'btn btn-primary btn-form-about');
        echo $form;
    }
}

