<?php 
namespace Vigas\Application\Model;

/**
 * Trait CurlRequest.
 * Builds and sends an HTTP(S) request, used to get json data from streaming platforms
 */
trait CurlRequest
{
    /**
    * @param string $url URL to send the request to
    * @param string|null $post_data POST data to send
    * @param string|null $http_header HTTP header to set
    * @return string Returns http(s) response
    */
    public function curlRequest($url, $post_data = null, $http_header = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if(!is_null($post_data))
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        }
        if(!is_null($http_header))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $http_header);
        }		
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

}
