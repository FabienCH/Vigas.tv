<?php
namespace Vigas\Application\Controller;

/**
* Class AppController.
* Validates that data sent via HTML forms are in the expected format
*/
class FormValidator
{
	/**
    * Checks that the parameter match the email format
    * @param string $email Email address to check
    * @return boolean Returns true if the parameter match the email format, false otherwise
    */
    public function checkEmail($email)
    {
        if(preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,20}$#", $email))
        {
            return true; 
        }
        else
        {
            return false;
        }
    }
    
	/**
    * Checks that the parameter is an alphanumeric string
    * @param string $string The string to check
    * @return boolean Returns true if the parameter is an alphanumeric string, false otherwise
    */
    public function checkAlphanum($string)
    {
        if(preg_match("#^[a-zA-Z0-9_]*$#", $string))
        {
            return true; 
        }
        else
        {
            return false;
        } 
    }
    
	/**
    * Checks that the parameter length is between the specified min and max value
    * @param string $string The string to check
    * @param int $min The minimal lenght
    * @param int $max The maximal lenght
    * @return boolean Returns true if the parameter length is between the specified min and max value, false otherwise
    */
    public function checkLength($string, $min, $max)
    {
        if(strlen($string) >= $min && strlen($string) <= $max)
        {
            return true;  
        }
        else
        {
            return false;        
        }
    }
    
}

