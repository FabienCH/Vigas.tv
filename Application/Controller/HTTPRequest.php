<?php
namespace Vigas\Application\Controller;

/**
* Class HTTPRequest.
* Manages HTTP request
*/
class HTTPRequest
{
	/**
    * @var array HTTP POST parameters
    */
	protected $post_data=[];
	
	/**
    * @var array HTTP GET parameters
    */
	protected $get_data=[];
	
	/**
    * Sets HTTP POST and GET parameters
    */
	public function __construct()
    {
        $this->setGetData();
        $this->setPostData();
    }

	/**
    * Sets get_data attribute
    */
	public function setGetData()
	{
		foreach ($_GET as $key => $value)
		{
			if($key != "source_json")
			{
				$this->get_data[$key] = htmlspecialchars($value);
			}
			elseif(isset($_GET["source_json"]) && is_array(json_decode($_GET["source_json"])))
			{
				$this->get_data['source_json'] = json_decode($_GET["source_json"]);
			}		
		}	
	}
	
	/**
    * Sets post_data attribute
    */
	public function setPostData()
	{
		foreach ($_POST as $key => $value)
		{
			$this->post_data[$key] = htmlspecialchars($value);
		}
	}
	
	/**
    * @return array get_data attribute
    */
	public function getGetData()
	{
		return $this->get_data;
	}
	
	/**
    * @return array post_data attribute
    */
	public function getPostData()
	{
		return $this->post_data;
	}
	
}