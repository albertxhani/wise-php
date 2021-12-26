<?php

namespace TransferWise\Service;

class ValidatorService extends Service
{

    /**
     * Validate Sort code
     *
     * @param Integer $id Sort Code
     *
     * @return Response
     */
    public function sortcode($id)
    {
        return $this->client->request(
            "GET",
            "v1/validators/sort-code?sortCode={$id}"
        );
    }

    /**
     * Validate account number
     *
     * @param Integer $number Account Number
     *
     * @return Response
     */
    public function accountNo($number)
    {
        return $this->client->request(
            "GET",
            "v1/validators/sort-code-account-number?accountNumber={$number}"
        );
    }

    /**
     * Validate iban
     *
     * @param String $id Iban
     *
     * @return Response
     */
    public function iban($id)
    {
        return $this->client->request(
            "GET",
            "v1/validators/iban?iban={$id}"
        );
    }

    /**
     * Validate iban and bic code
     *
     * @param String $iban Iban
     * @param String $bic  Bic
     *
     * @return Response
     */
    public function ibanandbic($iban, $bic)
    {
        return $this->client->request(
            "GET",
            "v1/validators/bic?bic={$bic}&iban={$iban}"
        );
    }

    /**
     * Validate aba code
     *
     * @param String $id Aba code
     *
     * @return Response
     */
    public function aba($id)
    {
        return $this->client->request(
            "GET",
            "v1/validators/abartn?abartn={$id}"
        );
    }


    /**
     * Validate aba account number
     *
     * @param String $acc_no Aba account no
     *
     * @return Response
     */
    public function abaAccountNo($acc_no)
    {
        return $this->client->request(
            "GET",
            "v1/validators/aba-account-number?accountNumber={$acc_no}"
        );
    }


    /**
     * Validate aba account number
     *
     * @param String $ifsc IFCS code
     *
     * @return Response
     */
    public function ifsc($ifsc)
    {
        return $this->client->request(
            "GET",
            "v1/validators/ifsc-code?ifscCode={$ifsc}"
        );
    }

    /**
     * Get list of banks by country
     *
     * @param String $country Contry Code
     *
     * @return Response
     */
    public function banks($country)
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