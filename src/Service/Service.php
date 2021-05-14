<?php

namespace Wise\Service;

class Service
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

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