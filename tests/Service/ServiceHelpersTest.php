<?php

declare(strict_types=1);

namespace TransferWise\Tests\Service;

use TransferWise\Client;
use TransferWise\Exception\InvalidArgumentException;
use TransferWise\Service\Service;
use TransferWise\Tests\TestCase;

final class ServiceHelpersTest extends TestCase
{
    public function testWithQueryAppendsParameters(): void
    {
        $client = $this->createMock(Client::class);
        $helper = new class ($client) extends Service {
            public function exposeWithQuery(string $path, array $query): string
            {
                return $this->withQuery($path, $query);
            }
        };

        self::assertSame(
            'v1/transfers?limit=10&offset=0',
            $helper->exposeWithQuery('v1/transfers', ['limit' => 10, 'offset' => 0])
        );
    }

    public function testWithQueryReturnsPathUnchangedWhenEmpty(): void
    {
        $client = $this->createMock(Client::class);
        $helper = new class ($client) extends Service {
            public function exposeWithQuery(string $path, array $query): string
            {
                return $this->withQuery($path, $query);
            }
        };

        self::assertSame('v1/transfers', $helper->exposeWithQuery('v1/transfers', []));
    }

    public function testMustHaveProfileIdFromClient(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('getProfileId')->willReturn(42);
        $helper = new class ($client) extends Service {
            public function exposeMust(mixed $id = false): mixed
            {
                return $this->mustHaveProfileId($id);
            }
        };

        self::assertSame(42, $helper->exposeMust(false));
        self::assertSame(99, $helper->exposeMust(99));
    }

    public function testMustHaveProfileIdThrowsWhenMissing(): void
    {
        $client = $this->createMock(Client::class);
        $client->method('getProfileId')->willReturn(null);
        $helper = new class ($client) extends Service {
            public function exposeMust(mixed $id = false): mixed
            {
                return $this->mustHaveProfileId($id);
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing profile id');
        $helper->exposeMust(false);
    }
}
