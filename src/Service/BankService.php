<?php

namespace TransferWise\Service;

class BankService extends Service
{

    /**
     * Get list of banks by country
     *
     * @param String $country Contry Code
     *
     * @return Response
     */
    public function byCountry($country)
    {
        return $this->client->request(
            "GET",
            "v1/banks?country={$country}"
        );
    }

    /**
     * Branch name based on country and code
     *
     * @param String $country Country Code
     * @param String $code    Bank Code
     *
     * @return Response
     */
    public function branch($country, $code)
    {
        return $this->client->request(
            "GET",
            "v1/bank-branches?country={$country}&bankCode={$code}"
        );
    }
}