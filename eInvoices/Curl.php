<?php

namespace eInvoices;

/**
 * Curl helper
 *
 * @author DigiWallet B.V.
 * @license BSD License
 * @package eInvoices API
 *
 * @property $url string URL to make request to
 * @property $sslVerifyPeer bool Whether to check the peer's SSL certificate
 * @property $timeout int Wait timeout
 * @property $method string HTTP request method (POST, GET, etc..)
 * @property $params array Array of parameters to send in POST string
 */
class Curl
{
    public $url = '';
    public $sslVerifyPeer = false;
    public $timeout = 10;
    public $method = 'POST';
    public $params = [];

    /**
     * Handle container
     */
    protected $handle;

    /**
     * Execute
     * @return string Result
     * @throws Exception
     */
    public function exec()
    {
		// Make query
 		$fields = '';
   		foreach ($this->params as $key => $value) {
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
		curl_setopt($this->handle, CURLOPT_POST, !empty($fields));
		curl_setopt($this->handle, CURLOPT_POSTFIELDS, $fields);
		$result = curl_exec($this->handle);

		if ($result === false) throw new Exception(curl_error($this->handle));
		curl_close($this->handle);
		return $result;
	}
}
