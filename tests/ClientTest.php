<?php

declare(strict_types=1);

namespace TransferWise\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use TransferWise\Client;
use TransferWise\Exception\InvalidArgumentException;

final class ClientTest extends TestCase
{
    public function testConstructorWithArrayToken(): void
    {
        $client = new Client(['token' => 'abc']);
        self::assertNull($client->getProfileId());
    }

    public function testConstructorWithStringToken(): void
    {
        $client = new Client('bare-token');
        self::assertNull($client->getProfileId());
    }

    public function testConstructorMissingTokenThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing token');
        new Client(['profile_id' => 1]);
    }

    public function testSandboxUrlUsed(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{}'),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client([
            'token' => 'abc',
            'env' => 'sandbox',
        ]);
        $this->injectGuzzleClient($client, $guzzle);
        $client->request('GET', 'v1/profiles');

        $last = $mock->getLastRequest();
        self::assertNotNull($last);
        $uri = (string) $last->getUri();
        self::assertStringStartsWith('https://api.sandbox.transferwise.tech/', $uri);
    }

    public function testRequestSendsAuthHeader(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{}'),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 'test-token']);
        $this->injectGuzzleClient($client, $guzzle);
        $client->request('GET', 'v1/profiles');

        $last = $mock->getLastRequest();
        self::assertNotNull($last);
        self::assertSame('Bearer test-token', $last->getHeaderLine('Authorization'));
    }

    public function testRequestSendsJsonBodyOnPost(): void
    {
        $mock = new MockHandler([
            new Response(200, [], '{}'),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);
        $client->request('POST', 'v1/profiles', ['name' => 'foo']);

        $last = $mock->getLastRequest();
        self::assertNotNull($last);
        self::assertSame(
            ['name' => 'foo'],
            json_decode((string) $last->getBody(), true, 512, JSON_THROW_ON_ERROR)
        );
    }

    public function testResponseDecodesJson(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 42])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);
        $result = $client->request('GET', 'v1/x');
        self::assertSame(['id' => 42], $result);
    }
}
