<?php

namespace Wise\Factory;

class ServiceFactory
{

    private static $services = [
        'profiles' => \Wise\Service\ProfileService::class,
        'quotes' => \Wise\Service\QuoteService::class,
        "recipient_accounts" => \Wise\Service\RecipientAccountService::class,
        "transfers" => \Wise\Service\TransferService::class,
        "validators" => \Wise\Service\ValidatorService::class,
        "banks" => \Wise\Service\BankService::class,
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