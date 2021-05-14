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

    public function __construct($client)
    {
        $this->_client = $client;
    }

    protected function getService($name)
    {
        return array_key_exists($name, self::$services) ?
            self::$services[$name] : false;
    }

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