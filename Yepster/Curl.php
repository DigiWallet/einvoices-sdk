<?php
namespace Yepster;

/**
 * Curl helper
 *
 * @author  Yellow Melon B.V.
 * @license BSD License
 * @version 1.0
 */

class Curl
{
    /**
     *  Properties
     */

    public $url = "";					// URL here
    public $sslVerifyPeer = false;		// Verify peers
    public $timeout = 10;				// Timeout in seconds
    public $method = "POST";			// Or GET
    public $params = [];				// Parameters to post

    /**
     *  Handle container
     */

    protected $handle; 

    /**
     *  Execute
     *  @return string Result
     */

    public function exec()
    {
		// Make query
 		$fields = '';
   		foreach($this->params as $key => $value) { 
      		$fields .= $key . '=' . $value . '&'; 
   		}
   		rtrim($fields, '&');

   		// Prepare the call & run it
    	$this->handle = curl_init();
    	curl_setopt($this->handle, CURLOPT_URL, $this->url);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
		curl_setopt($this->handle, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($this->handle, CURLOPT_HEADER, 0);
		curl_setopt($this->handle, CURLOPT_POST, count($fields));
		curl_setopt($this->handle, CURLOPT_POSTFIELDS, $fields);
		$result = curl_exec($this->handle);

		if ($result === false) throw new Exception(curl_error());
		curl_close($this->handle);
		return $result;
	}

}