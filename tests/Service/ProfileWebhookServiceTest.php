<?php

declare(strict_types=1);

namespace TransferWise\Tests\Service;

use TransferWise\Client;
use TransferWise\Service\ProfileWebhookService;
use TransferWise\Tests\TestCase;

final class ProfileWebhookServiceTest extends TestCase
{
    public function testRetrieveCallsCorrectEndpoint(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('getProfileId')->willReturn(42);
        $client->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                'v3/profiles/42/subscriptions/99'
            )
            ->willReturn([]);

        $svc = new ProfileWebhookService($client);
        $svc->retrieve(99, false);
    }

    public function testListCallsCorrectEndpoint(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('getProfileId')->willReturn(42);
        $client->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                'v3/profiles/42/subscriptions'
            )
            ->willReturn([]);

        $svc = new ProfileWebhookService($client);
        $svc->list(false);
    }

    public function testCreateBuildsCorrectPayload(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('getProfileId')->willReturn(42);
        $client->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                'v3/profiles/42/subscriptions',
                [
                    'name' => 'hook',
                    'trigger_on' => 'transfer.completed',
                    'delivery' => [
                        'version' => '2.0.0',
                        'url' => 'https://example.com/hook',
                    ],
                ]
            )
            ->willReturn([]);

        $svc = new ProfileWebhookService($client);
        $svc->create([
            'name' => 'hook',
            'event' => 'transfer.completed',
            'url' => 'https://example.com/hook',
        ], false);
    }

    public function testDeleteCallsCorrectEndpoint(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('getProfileId')->willReturn(42);
        $client->expects(self::once())
            ->method('request')
            ->with(
                'DELETE',
                'v3/profiles/42/subscriptions/99'
            )
            ->willReturn([]);

        $svc = new ProfileWebhookService($client);
        $svc->delete(99, false);
    }
}
