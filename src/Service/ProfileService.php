<?php

namespace Wise\Service;

class ProfileService
{

    public function create($params)
    {
        return $this->client->request("POST", "v1/profiles", $params);
    }

    public function update($params)
    {
        return $this->client->request("PUT", "v1/profiles", $params);
    }

    public function all()
    {
        return $this->client->request("GET", "v1/profiles");
    }

    public function retreive($id)
    {
        return $this->client->request("GET", "v1/profiles/{$id}");
    }

    public function directors($id)
    {
        return $this->client->request("GET", "v1/{$id}/directors")
    }

}