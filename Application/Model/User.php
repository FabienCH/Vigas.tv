<?php
namespace Vigas\Application\Model;

use  Vigas\Application\Application;

/**
* Class User.
* Modifies user informations in the database
*/
class User
{
    /**
    * @var int The user id
    */
    protected $id;
    
    /**
    * @var string The user username 
    */
    protected $username;
    
    /**
    * @var string The user email address
    */
    protected $email;
    
    /**
    * @var boolean True if the user linked his streaming platform account(s) at least once, false otherwise
    */
    protected $first_link_done;
    
    /**
    * @var string Reset password token
    */
    protected $reset_pwd_token;
    
    /**
    * @var string Reset password token expiration datetime 
    */
    protected $reset_pwd_token_endat;
	
	/**
    * @var array All the streaming platforms accounts the user linked with his vigas account
    */
    protected $platform_accounts = [];

	/**
    * Builds an array of SQL query WHERE condition(s)
	* @param array $conditions The SQL query WHERE condition(s)
    * @return array An array containing condition(s) as a string and an array of value(s)
    */
    private function builRequestConditions($conditions)
    {
        $request_data = ['conditions' => '', 'value' => []];
        $i = 0;
        $lenght = count($conditions);
        foreach($conditions as $column => $value)
        {
            $request_data['conditions'] .=  $column.' = :'.$column;
            if($i != $lenght -1)
            {
                 $request_data['conditions'] .= ' AND ';
            }     
            $request_data['value'][$column] = $value;
            $i++;
        }
        return $request_data;
    }
	
	/**
    * Logs the datetime into the database when a user just logged in 
    * @param PDO $db Database connection object
    */
	public function logUserLogin(\PDO $db)
	{
		$now = date("Y-m-d H:i:s", strtotime('now'));
        $req = $db->prepare('UPDATE User SET last_logon=:last_logon WHERE id= :id');
        $resultat = $req->execute(array(
            'last_logon' => $now,
            'id' => $this->id
        ));
	}
    
    /**
    * Gets user from the database and set User object properties
    * @param PDO $db database connection object
    * @param array $conditions The SQL condition(s) to select the user
    * @param string $password The user password
    * @return boolean Returns false if user has not been found
    */
    public function getUser(\PDO $db, $conditions, $password = null)
    {
        $request_data = $this->builRequestConditions($conditions);
        $req = $db->prepare('SELECT * FROM User WHERE '.$request_data['conditions']);
        $user_data = $req->execute($request_data['value']);
        $user_data = $req->fetch();
		if(!$user_data)
        {
            return false;
        }
		if($password !== null)
		{
			if(!password_verify($password, $user_data['password']))
			{
				return false;
			}
		}
		$this->id = $user_data['id'];
		$this->username = $user_data['username'];
		$this->email = $user_data['email'];
		$this->first_link_done = $user_data['first_link_done'];
		$this->reset_pwd_token = $user_data['reset_pwd_token'];
		$this->reset_pwd_token_endat = $user_data['reset_pwd_token_endat'];
    }

    /**
    * Inserts a new user account into the database
    * @param PDO $db Database connection object
    * @param string $username The user username 
    * @param string $email The user email address
    * @param string $password The user password
    * @return boolean Returns true if account has been created, false otherwise
    */
    public function insertUser(\PDO $db, $username, $email, $password)
    {
        $req = $db->prepare('INSERT INTO User(username, email, password) VALUES(:username, :email, :password)');
        $resultat = $req->execute(array(
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ));

        if (!$resultat)
        {
            return false;
        }
        else
        {
            return true;
        }	
    }

    /**
    * Changes a user password
    * @param PDO $db Database connection object
	* @param array $conditions The SQL condition(s) to select the user
    * @param string $new_password The new user password
    * @return boolean Returns true if the password has been changed, false otherwise
    */
    public function updatePassword(\PDO $db, $conditions, $new_password)
    {
        $request_data = $this->builRequestConditions($conditions);
        $request_data['value']['new_password'] = password_hash($new_password, PASSWORD_DEFAULT);
        $req = $db->prepare('UPDATE User SET password=:new_password WHERE '.$request_data['conditions']);
        $resultat = $req->execute($request_data['value']);
        if (!$resultat)
        {
            return false;
        }
        else
        {
            return true;
        }	    
    }
    
	 /**
    * Deletes the reset password token after the user set up a new password
    * @param PDO $db Database connection object
	* @param int $id The user id
    * @return boolean Returns true if reset_pwd_token and reset_pwd_token_endat columns has been set to NULL, false otherwise
    */
    public function deleteResetPwdToken(\PDO $db, $id)
    {
        $req = $db->prepare('UPDATE User SET reset_pwd_token=NULL, reset_pwd_token_endat=NULL WHERE id=:id');
        $resultat = $req->execute(array(
            'id' => $id
        ));
        if (!$resultat)
        {
            return false;
        }
        else
        {
            return true;
        }	    
    }

    /**
    * Checks if an account already exist with the given username
    * @param PDO $db Database connection object
    * @param string $username The username address to check
    * @return boolean Returns true if the username is unique (not found in database), false otherwise
    */
    public function checkUniqueUsername(\PDO $db, $username)
    {
        $user_data = $this->getUser($db, ['username' => $username]);
        if($user_data === false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
    * Checks if an account already exist with the given email address
    * @param PDO $db Database connection object
    * @param string $email The email address to check
    * @return boolean Returns true if the email address is unique (not found in database), false otherwise
    */
    public function checkUniqueEmail(\PDO $db, $email)
    {
        $user_data = $this->getUser($db, ['email' => $email]);
        if($user_data === false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
    * Sets first_link_done column to true
    * @param PDO $db Database connection object
    * @param int $id The user id
    * @return boolean Returns true if first_link_done has been set to true, false otherwise
    */
    public function firstLinkDone(\PDO $db, $id)
    {	
        $user_data = $this->getUser($db, ['id' => $id]);
        if($user_data !== false)
        {
            $req = $db->prepare('UPDATE User SET first_link_done=TRUE WHERE id= :id');
            $resultat = $req->execute(array(
                    'id' => $id
            ));
            $this->first_link_done = true;
            return $this->first_link_done;
        }
        else
        {
            return false;
        }
    }

    /**
    * Saves reset password token and reset password token expiration datetime into database
    * @param PDO $db Database connection object
    * @param string $token The reset password token
    * @param int $id The user id
    * @return boolean returns true if reset password token and reset password token expiration datetime has been saved, false otherwise
    */
    public function saveResetPwdToken(\PDO $db, $token, $id)
    {	
        $token_endat = date("Y-m-d H:i:s", strtotime('now +30 Minutes'));
        $req = $db->prepare('UPDATE User SET reset_pwd_token=:token, reset_pwd_token_endat=:token_endat WHERE id= :id');
        $resultat = $req->execute(array(
            'token' => $token,
            'token_endat' => $token_endat,
            'id' => $id
        ));

        if (!$resultat)
        {
            return false;
        }
        else
        {
            return true;
        }	
    }
	
	/**
    * Adds a streaming platform account into array $platform_accounts
	* @param Platform $account The streaming platform account to add
    */
    public function addPlatformAccounts($account)
    {
		$key = explode('\\', get_class($account));
		$key = end($key);
		$this->platform_accounts[$key] = $account;
    }

    /**
    * @return int The user id
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * @return string The user username 
    */
    public function getUsername()
    {
            return $this->username;
    }

    /**
    * @return string The user email address
    */
    public function getEmail()
    {
        return $this->email;
    }

    /**
    * @return boolean True if the user linked his streaming platform account(s) at least once, false otherwise
    */
    public function getFirstLinkDone()
    {
        return $this->first_link_done;
    }

    /**
    * @return string Reset password token
    */
    public function getResetPwdToken()
    {
        return $this->reset_pwd_token;
    }

    /**
    * @return string Reset password token expiration datetime 
    */
    public function getResetPwdTokenEndat()
    {
        return $this->reset_pwd_token_endat;
    }
	
	/**
    * @return array All the streaming platforms accounts the user linked with his vigas account
    */
    public function getPlatformAccounts()
    {
        return $this->platform_accounts;
    }

}
