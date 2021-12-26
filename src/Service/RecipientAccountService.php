<?php

namespace TransferWise\Service;

class RecipientAccountService extends Service
{
    /**
     * Create a recipient account
     *
     * @param Array $params 
     *
     * @return Response
     */
    public function create($params)
    {
        return $this->client->request("POST", "v1/accounts", $this->validate($params));
    }

    /**
     * Create a recipient account
     *
     * @param Array $query Paging params 
     *
     * @return Response
     */
    public function all($query = [])
    {
        $path = $this->withQuery("v2/accounts", $query);
        return $this->client->request("GET", $path);
    }

    /**
     * Create a single recipient account by id
     *
     * @param Integer $id Account Id
     *
     * @return Response
     */
    public function retrieve($id)
    {
        return $this->client->request("GET", "v1/accounts/{$id}");
    }

    /**
     * Delete recipient account by id
     *
     * @param Integer $id Account Id
     *
     * @return Response
     */
    public function delete($id)
    {
        return $this->client->request("DELETE", "v1/accounts/{$id}");
    }
}