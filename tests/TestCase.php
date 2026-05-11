<?php

declare(strict_types=1);

namespace TransferWise\Tests;

use GuzzleHttp\Client as GuzzleClient;
use TransferWise\Client;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function injectGuzzleClient(Client $client, GuzzleClient $guzzle): void
    {
        \Closure::bind(function () use ($guzzle) {
            $this->_http_client = $guzzle;
        }, $client, Client::class)();
    }
}
