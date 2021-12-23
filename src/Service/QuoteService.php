<?php

namespace Wise\Service;

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