<?php

namespace TransferWise;

class Client
{

    private $_token;

    private $_profile_id;

    private $_factory = null;

    private $_http_client = false;

    private $_url = "https://api.transferwise.com/";

    /**
     * Initialise Client
     *
     * @param String $token Logged in token
     */
    public function __construct($config)
    {
        if (is_array($config)) {

            if (!isset($config["token"])) {
                throw new Exception\InvalidArgumentException("missing token");
            }
            $this->_token = $config["token"];

            if (isset($config["profile_id"])) {
                $this->_profile_id = $config["profile_id"];
            }

            if (isset($config["env"]) && $config["env"] == "sandbox") {
                $this->_url = "https://api.sandbox.transferwise.tech/";
            }

            return;
        }

        $this->_token = $config;
    }

    /**
     * Get an exposed service
     *
     * @param String $name Service name
     *
     * @return Service
     */
    public function __get($name)
    {
        if ($this->_factory === null) {
            $this->_factory = new \TransferWise\Factory\ServiceFactory($this);
        }

        return $this->_factory->__get($name);
    }

    /**
     * Returns Profile ID
     *
     * @return Integer
     */
    public function getProfileId()
    {
        return $this->_profile_id;
    }

    /**
     * Request Call
     *
     * @param String $method GET|POST|PATCH
     * @param String $path   Api route
     * @param Array  $params request params
     * @param Array  $headers additional request headers
     *
     * @return Json
     */
    public function request($method, $path, $params = [], $headers = [])
    {

        if (!$this->_http_client) {
            $this->_http_client = new \GuzzleHttp\Client();
        }

        $data = [
            'headers' => array_merge([
                'Authorization' => "Bearer $this->_token",
                'Content-Type' => "application/json",
            ], $headers)
        ];

        if ((in_array($method, ["POST", "PUT", "PATCH"]))  && count($params) > 0) {
            $data["json"] = $params;
        }

        try {
            $response = $this->_http_client->request(
                $method,
                $this->_url . $path,
                $data
            );
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return $this->handleErrors($exception);
        }

        return $this->response($response);
    }


    public function response($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    public function handleErrors($exception)
    {
        $code = $exception->getCode();
        $content = $exception->getResponse()->getBody()->getContents();
        $response = json_decode($content);

        if (($code === 400 || $code === 404) && $content !== "") {
            $message = isset($response->errors[0]->message) ? $response->errors[0]->message : $response->message ?? 'Unknown error';
            throw new \TransferWise\Exception\BadException($message, $code);
        }

        if ($code === 422) {
            if ($content !== "") {
                $errors = isset($response->errors) ? $response->errors : [];
                throw \TransferWise\Exception\ValidationException::instance(
                    "Validation error",
                    $errors,
                    $code
                );
            } else {
                $message = isset($response->message) ? $response->message : 'Validation error';
                throw new \TransferWise\Exception\ValidationException($message, $code);
            }
        }

        if ($code === 401 && $content !== "") {
            $message = isset($response->message) ? $response->message : 'Authorization error';
            throw new \TransferWise\Exception\AuthorisationException($message, $code);
        }

        if ($code === 403) {
            $headers = $exception->getResponse()->getHeaders();
            $approvalResult = isset($headers['x-2fa-approval-result']) ? $headers['x-2fa-approval-result'][0] : null;
            $approvalToken = isset($headers['x-2fa-approval']) ? $headers['x-2fa-approval'][0] : null;
            
            // Check for Strong Customer Authentication (SCA) related 403 errors
            if ($approvalResult === 'REJECTED') {
                $message = 'Access denied: Strong Customer Authentication required. ';
                if ($approvalToken) {
                    $message .= 'Use the One Time Token (OTT) to complete the authentication challenge: ' . $approvalToken;
                } else {
                    $message .= 'Additional verification is required.';
                }
                throw new \TransferWise\Exception\AccessException($message, $code);
            } elseif ($approvalResult === 'APPROVED') {
                $message = 'Access denied: The authentication was approved but access is still restricted. Please retry your request.';
                throw new \TransferWise\Exception\AccessException($message, $code);
            }
            
            // Fallback to original error message if SCA headers are not present
            $message = isset($response->errors[0]->message) ? $response->errors[0]->message : $response->message ?? 'Access denied';
            throw new \TransferWise\Exception\AccessException($message, $code);
        }

        throw new \Exception($exception->getMessage(), $code);
    }

}
