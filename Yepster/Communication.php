<?php
namespace Yepster;

/**
 * Handle communication with Yepster over https
 *
 * @author  Yellow Melon B.V.
 * @license BSD License
 * @version 1.0
 */

abstract class Communication extends Curl
{
    /**
     *  Constants
     */

    const  YEPSTER_BASE_URL = "https://yepster.com/api/";

    // const YEPSTER_ACTION must be defined!

    /**
     *  Variables
     */

    protected $action = "";    // Action to call
    protected $apiKey = null;  // API key container
    protected $response = "";  // Last response

    /**
     *  Parameter getter
     *  @param string Parameter name
     *  @return Array Parameter value
     */

    public function getParameter ($name) {
        return (isset($this->params[$name])) ? $this->params[$name] : null;
    }    

    /**
     *  Parameter setter
     *  @param string $param Parameter name
     *  @param mixed $param Parameter value
     *  @return Communication For chaining
     * 
     */

    public function setParameter ($name, $value) {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     *  Flatten parameter array 
     *  @return Array
     */

    public function flattenItem ($key, $values) {
        $output = [];
        foreach ($values as $subkey => $value) {
            $newkey = $key . "[" . $subkey . "]";
            if (!is_array($value)) 
                $output[$newkey] = $value; else
                $output = array_merge($output, $this->flattenItem($newkey, $value) );                            
        }
        return $output;
    }

    /**
     *  Convert the rawParams array to a 1-level params item
     *  @return $this
     */

    private function flattenParams () {
        // Flatten
        $flatParameters = [];
        foreach ($this->params as $key => $value) 
            if (is_array($value))
                $flatParameters = array_merge ($flatParameters, $this->flattenItem($key, $value)); else
                $flatParameters[$key] = $value;       
        $this->params = $flatParameters;
        return $this;
    }

    /**
     *  API key setter
     *  @param string $apiKey 64-character API key
     *  @return Communication For chaining
     */

    public function setApiKey ($apiKey) {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     *  Action setter
     *  @param string $apiKey 64-character API key
     *  @return Communication For chaining
     */

    public function setAction ($apiKey) {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     *  Exec
     *  @return array [status => 'success|fail', 'error' => '...' ]
     */

    public function exec() {
        if (!$this->action) throw new Exception ('No action set');
        if (!$this->apiKey) throw new Exception ('You need to set an API key');
        $this->setParameter('apikey', $this->apiKey);

        $this->flattenParams();
        $this->url = self::YEPSTER_BASE_URL . $this->action;

        $response = parent::exec();
        $this->response = $response;
        return json_decode ($response);
    }

}

