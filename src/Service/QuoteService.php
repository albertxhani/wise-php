<?php

namespace Wise\Service;

class QuoteService extends Service
{

    public function create($params)
    {
        return $this->client->request("POST", "v2/quotes", $this->validate($params));
    }

    public function retreive($id)
    {
        return $this->client->request("GET", "v2/quotes/{$id}");
    }

}