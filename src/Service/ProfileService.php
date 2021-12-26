<?php

namespace TransferWise\Service;

class ProfileService extends Service
{
    /**
     * Create Profile
     *
     * @param Array $params parameters needed to create a profile
     *
     * @return Response
     */
    public function create($params)
    {
        return $this->client->request("POST", "v1/profiles", $params);
    }

    /**
     * Update Profile
     *
     * @param Array $params Profile fields
     *
     * @return Response
     */
    public function update($params)
    {
        return $this->client->request("PUT", "v1/profiles", $params);
    }

    /**
     * Get all profiles
     *
     * @return Response
     */
    public function all()
    {
        return $this->client->request("GET", "v1/profiles");
    }

    /**
     * Retrieve profile by Id
     *
     * @param Int $id Profile Id
     *
     * @return Response
     */
    public function retrieve($id)
    {
        return $this->client->request("GET", "v1/profiles/{$id}");
    }

    /**
     * Get List of directors for a specific profile
     *
     * @param Int $id Profile Id
     *
     * @return Response
     */
    public function directors($id)
    {
        return $this->client->request("GET", "v1/{$id}/directors");
    }

}