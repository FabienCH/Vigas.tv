<?php
namespace Vigas\StreamingPlatforms\Model;

class Platform
{
	protected $name;
	
	protected $base_url;
	
	protected $http_header;
	
	protected $api_keys = [];
		
	public function __construct($name)
    {
        $this->name = $name;
		$xml_doc = new \DOMDocument;
		$xml_doc->load(__DIR__.'/../config.xml');
		$elements = $xml_doc->getElementsByTagName($name);
        for($i=0; $i<$elements->length; $i++)
		{
			if($elements->item($i)->getAttribute('name') == 'base_url')
			{
				$this->base_url = $elements->item($i)->getAttribute('value');
			}
			else
			{
				$this->api_keys[$elements->item($i)->getAttribute('name')] = $elements->item($i)->getAttribute('value');
			}
		}
		var_dump($this);
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
		var_dump($this->get_data);
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