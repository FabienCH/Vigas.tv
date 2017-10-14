<?php
namespace Vigas\Application\Controller;

class HTTPRequest
{
	protected $post_data=[];
	
	protected $get_data=[];
	
	public function __construct()
    {
        $this->setGetData();
        $this->setPostData();
    }
	
	public function cookieData($key)
	{
		return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
	}

	public function cookieExists($key)
	{
		return isset($_COOKIE[$key]);
	}

	public function setGetData()
	{
		foreach ($_GET as $key => $value)
		{
			$this->get_data[$key] = htmlspecialchars($value);
		}
	}
	
	public function setPostData()
	{
		foreach ($_POST as $key => $value)
		{
			$this->post_data[$key] = htmlspecialchars($value);
		}
	}
	
	public function getGetData()
	{
		return $this->get_data;
	}
	
	public function getPostData()
	{
		return $this->post_data;
	}
	
}