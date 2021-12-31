<?php

namespace TransferWise\Service;

class QuoteService extends Service
{

    /**
     * Create Quote
     *
     * @param Array $params parameters needed to create a quote
     *
     * @return Response
     */
    public function create($params)
    {
        return $this->client->request("POST", "v2/quotes", $this->validate($params));
    }

    /**
     * Create temporary quote
     *
     * @param Array $params parameters needed to create a temporary quote
     *
     * @return Response
     */
    public function temporary($params)
    {
        return $this->client->request("POST", "v2/quotes", $params);
    }

    /**
     * Update Quote
     *
     * @param Int   $id     Quote Id
     * @param Array $params parameters needed to update a quote
     *
     * @return Response
     */
    public function update($id, $params)
    {
        return $this->client->request("PATCH", "v2/quotes/{$id}", $params);
    }

    /**
     * Retrieve quote by id
     *
     * @param Int $id Quote Id
     *
     * @return Response
     */
    public function retrieve($id)
    {
        return $this->client->request("GET", "v2/quotes/{$id}");
    }

}