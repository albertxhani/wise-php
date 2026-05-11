<?php

declare(strict_types=1);

namespace TransferWise\Tests\Exception;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use TransferWise\Client;
use TransferWise\Exception\AccessException;
use TransferWise\Exception\AuthorisationException;
use TransferWise\Exception\BadException;
use TransferWise\Exception\ValidationException;
use TransferWise\Tests\TestCase;

final class ExceptionHandlingTest extends TestCase
{
    public function testBadExceptionOn400(): void
    {
        $mock = new MockHandler([
            new Response(400, [], json_encode(['errors' => [['message' => 'bad input']]])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(BadException::class);
        $this->expectExceptionMessage('bad input');
        $client->request('GET', 'v1/x');
    }

    public function testBadExceptionOn404(): void
    {
        $mock = new MockHandler([
            new Response(404, [], json_encode(['errors' => [['message' => 'not found']]])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(BadException::class);
        $this->expectExceptionMessage('not found');
        $client->request('GET', 'v1/x');
    }

    public function testValidationExceptionOn422WithErrors(): void
    {
        $mock = new MockHandler([
            new Response(422, [], json_encode(['errors' => [['message' => 'field required']]])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(ValidationException::class);
        try {
            $client->request('POST', 'v1/x', ['a' => 1]);
        } catch (ValidationException $e) {
            self::assertNotEmpty($e->getErrors());
            throw $e;
        }
    }

    public function testValidationExceptionOn422WithMessage(): void
    {
        $mock = new MockHandler([
            new Response(422, [], json_encode(['message' => 'validation failed'])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(ValidationException::class);
        // Client always uses the fixed caption for the non-empty 422 branch.
        $this->expectExceptionMessage('Validation error');
        $client->request('POST', 'v1/x', ['a' => 1]);
    }

    public function testValidationExceptionOn422EmptyBody(): void
    {
        $mock = new MockHandler([
            new Response(422, [], ''),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation error');
        $client->request('POST', 'v1/x', ['a' => 1]);
    }

    public function testAuthorisationExceptionOn401(): void
    {
        $mock = new MockHandler([
            new Response(401, [], json_encode(['message' => 'Unauthorized'])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(AuthorisationException::class);
        $this->expectExceptionMessage('Unauthorized');
        $client->request('GET', 'v1/x');
    }

    public function testAccessExceptionOn403Rejected(): void
    {
        $mock = new MockHandler([
            new Response(403, [
                'x-2fa-approval-result' => ['REJECTED'],
                'x-2fa-approval' => ['some-token'],
            ], '{}'),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage('some-token');
        $client->request('GET', 'v1/x');
    }

    public function testAccessExceptionOn403Approved(): void
    {
        $mock = new MockHandler([
            new Response(403, [
                'x-2fa-approval-result' => ['APPROVED'],
            ], '{}'),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage('approved but access is still restricted');
        $client->request('GET', 'v1/x');
    }

    public function testAccessExceptionOn403Fallback(): void
    {
        $mock = new MockHandler([
            new Response(403, [], json_encode(['message' => 'Forbidden fallback'])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage('Forbidden fallback');
        $client->request('GET', 'v1/x');
    }

    public function testGenericExceptionFallback(): void
    {
        $mock = new MockHandler([
            new Response(409, [], json_encode(['message' => 'conflict'])),
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);
        $client = new Client(['token' => 't']);
        $this->injectGuzzleClient($client, $guzzle);

        $this->expectException(\Exception::class);
        $this->expectExceptionCode(409);
        $client->request('GET', 'v1/x');
    }
}
