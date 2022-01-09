<?php

namespace TransferWise\Factory;

class ServiceFactory
{
    private $_client;

    private static $services = [
        'profiles' => \TransferWise\Service\ProfileService::class,
        'quotes' => \TransferWise\Service\QuoteService::class,
        "recipient_accounts" => \TransferWise\Service\RecipientAccountService::class,
        "transfers" => \TransferWise\Service\TransferService::class,
        "validators" => \TransferWise\Service\ValidatorService::class,
        "banks" => \TransferWise\Service\BankService::class,
        "profileWebhooks" => \TransferWise\Service\ProfileWebhookService::class
    ];

    private $instances = [];

    /**
     * Construct service
     *
     * @param Client $client Client Object
     *
     * @return void
     */
    public function __construct($client)
    {
        $this->_client = $client;
    }

    /**
     * Fetch service object given the service name
     *
     * @param String $name Service name
     *
     * @return Service
     */
    protected function getService($name)
    {
        return array_key_exists($name, self::$services) ?
            self::$services[$name] : false;
    }

    /**
     * Get servce by name from the pool of services. Initialize it
     * if it has not been initialized before
     *
     * @param String $name Service name
     *
     * @return void
     */
    public function __get($name)
    {
        $className = $this->getService($name);

        if ($className) {
            if (!array_key_exists($name, $this->instances)) {
                $this->instances[$name] = new $className($this->_client);
            }
            return $this->instances[$name];
        }

        return null;
    }
}