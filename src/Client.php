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
     * @param String $method GET|POST
     * @param String $path   Api route
     * @param Array  $params request params
     *
     * @return Json
     */
    public function request($method, $path, $params = [])
    {

        if (!$this->_http_client) {
            $this->_http_client = new \GuzzleHttp\Client();
        }

        $data = [
            'headers' => [
                'Authorization' => "Bearer $this->_token",
                'Content-Type' => "application/json"
            ]
        ];

        if ($method == "PATCH") {
            $data["headers"]["Content-Type"] = "application/merge-patch+json";
        }

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
            throw new \TransferWise\Exception\BadException($response->errors[0]->message, $code);
        }

        if ($code === 422) {
            if ($content !== "") {
                throw \TransferWise\Exception\ValidationException::instance(
                    "Validation error",
                    $response->errors,
                    $code
                );
            } else {
                throw new \TransferWise\Exception\ValidationException($response->message, $code);
            }
        }

        if ($code === 401 && $content !== "") {
            throw new \TransferWise\Exception\AuthorisationException($response->message, $code);
        }

        if ($code === 403) {
            throw new \TransferWise\Exception\AccessException(
                $response->errors[0]->message,
                $code
            );
        }

        throw new \Exception($exception->getMessage(), $code);
    }

}
