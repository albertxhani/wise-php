<?php

namespace Wise\Service;

class Service
{
    protected $client;

    /**
     * Constructor
     *
     * @param Client $client Client object
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Validate parameters passed to call the wise api
     *
     * @param Array $params list of parameters
     *
     * @return Array
     */
    protected function validate($params)
    {
        $profile_id = $this->client->getProfileId();

        if (!$profile_id) {
            throw new \Wise\Exception\InvalidArgumentException("missing profile id");
        }

        if (!array_key_exists("profile", $params)) {
            $params["profile"] = $profile_id;
        }

        return $params;
    }

    /**
     * Add query parameters to url
     *
     * @param String $path  Base url
     * @param Array  $query List of parameters as queries
     *
     * @return String
     */
    protected function withQuery($path, $query)
    {

        if (count($query) == 0) {
            return $path;
        }

        $path .= "?";
        foreach ($query as $key => $value) {
            $path .= "{$key}={$value}&";
        }

        return substr_replace($path, "", -1);
    }

}