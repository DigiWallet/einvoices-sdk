<?php

namespace eInvoices;

/**
 * Handle communication with eInvoices over https
 *
 * @author DigiWallet B.V.
 * @license BSD License
 * @package eInvoices API
 *
 * @property $action string Action to call
 * @property $apiKey string API key of the relevant account
 * @property $response string HTTP response body
 */
abstract class Communication extends Curl
{
    const  EINVOICES_BASE_URL = 'https://www.e-facturen.nl/api/';
    // const YEPSTER_ACTION must be defined!

    protected $action = '';
    protected $apiKey = null;
    protected $response = '';

    /**
     *  Parameter getter
     * @param $name string Parameter name
     * @return mixed Parameter value
     */
    public function getParameter($name)
    {
        return (isset($this->params[$name])) ? $this->params[$name] : null;
    }

    /**
     *  Parameter setter
     * @param $name
     * @param $value
     * @return $this For chaining
     */
    public function setParameter($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * Recursively flatten parameter array
     * @param $key
     * @param $values
     * @return array
     */
    public function flattenItem($key, $values)
    {
        $output = [];
        foreach ($values as $subkey => $value) {
            $newkey = $key . '[' . $subkey . ']';
            if (!is_array($value)) {
                $output[$newkey] = $value;
            }
            else {
                $output = array_merge($output, $this->flattenItem($newkey, $value));
            }
        }
        return $output;
    }

    /**
     * Convert the rawParams array to a 1-level params item
     * @return $this
     */
    private function flattenParams()
    {
        // Flatten
        $flatParameters = [];
        foreach ($this->params as $key => $value) {
            if (is_array($value)) {
                $flatParameters = array_merge($flatParameters, $this->flattenItem($key, $value));
            }
            else {
                $flatParameters[$key] = $value;
            }
        }

        $this->params = $flatParameters;

        return $this;
    }

    /**
     * API key setter
     * @param string $apiKey 64-character API key
     * @return $this For chaining
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Action setter
     * @param string $apiKey 64-character API key
     * @return $this For chaining
     */
    public function setAction($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Exec
     * @return array [status => 'success|fail', 'error' => '...' ]
     * @throws Exception
     */
    public function exec()
    {
        if (!$this->action) {
            throw new Exception ('No action set');
        }
        if (!$this->apiKey) {
            throw new Exception ('You need to set an API key');
        }
        $this->setParameter('apikey', $this->apiKey);

        $this->flattenParams();
        $this->url = self::EINVOICES_BASE_URL . $this->action;

        $response = parent::exec();
        $this->response = $response;

        return json_decode($response);
    }

}

