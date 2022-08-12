<?php

namespace TransferWise\Service;

class AddressService extends Service
{
    /**
     * Create Address
     *
     * @param Array $params parameters needed to create an address
     *
     * @return Response
     */
    public function create($params)
    {
        return $this->client->request(
            "POST",
            "v1/addresses",
            $this->validate(["details" => $params])
        );
    }

    /**
     * Get all addresses
     *
     * @return Response
     */
    public function all()
    {
        $profile_id = $this->client->getProfileId();

        return $this->client->request(
            "GET",
            "v1/addresses?profile={$profile_id}"
        );
    }

    /**
     * Retrieve address by Id
     *
     * @param Int $id Address Id
     *
     * @return Response
     */
    public function retrieve($id)
    {
        return $this->client->request(
            "GET",
            "v1/addresses/{$id}"
        );
    }

    /**
     * Get Address requirements
     * 
     * @return Response
     */
    public function requirements()
    {
        return $this->client->request(
            "GET",
            "v1/address-requirements"
        );
    }
}