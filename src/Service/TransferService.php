<?php

namespace TransferWise\Service;

class TransferService extends Service
{

    /**
     * Create a transfer
     *
     * @param Array $params Transfer data
     *
     * @return void
     */
    public function create($params)
    {
        return $this->client->request("POST", "v1/transfers", $this->validate($params));
    }

    /**
     * Check transfer for requirements
     *
     * @param Array $params Transfer data
     *
     * @return void
     */
    public function requirements($params)
    {
        return $this->client->request("POST", "v1/transfer-requirements", $params);
    }

    /**
     * Fund a transfer
     *
     * @param Integer $transfer_id Transfer id
     * @param Array   $params      Fund transfer
     * 
     * @return Response
     */
    public function fund($transfer_id, $params = ["type" => "BALANCE"])
    {
        $profile_id = $this->client->getProfileId();
        $path = "v3/profiles/{$profile_id}/transfers/{$transfer_id}/payments";

        return $this->client->request("POST", $path, $params);
    }

    /**
     * Cancel a transfer
     *
     * @param Integer $transfer_id Transfer id
     *
     * @return Response
     */
    public function cancel($transfer_id)
    {
        $path = "v1/transfers/{$transfer_id}/cancel";
        return $this->client->request("PUT", $path);
    }

    /**
     * Retrieve transfer object
     *
     * @param Integer $transfer_id Transfer id
     *
     * @return Response
     */
    public function retrieve($transfer_id)
    {
        $path = "v1/transfers/{$transfer_id}";
        return $this->client->request("GET", $path);
    }

    /**
     * Get issues of a transfer
     *
     * @param Integer $transfer_id Transfer id
     *
     * @return Response
     */
    public function issues($transfer_id)
    {
        $path = "v1/transfers/{$transfer_id}/issues";
        return $this->client->request("GET", $path);
    }

    /**
     * Get delivery estimate time of the transfer
     *
     * @param Integer $transfer_id Transfer id
     *
     * @return Response
     */
    public function deliveryEstimates($transfer_id)
    {
        $path = "v1/delivery-estimates/{$transfer_id}";
        return $this->client->request("GET", $path);
    }

    /**
     * List all transfers
     *
     * @param Array $params Paging data for transfers
     *
     * @return Response
     */
    public function list($params)
    {

        $defaults = [
            "offset" => isset($params["offset"]) ? $params["offset"] : 0,
            "limit" => isset($params["limit"]) ? $params["limit"] : 100,
            "profile" => $this->client->getProfileId()
        ];

        $path = $this->withQuery("v1/transfers", $defaults);
        return $this->client->request("GET", $path);
    }
}