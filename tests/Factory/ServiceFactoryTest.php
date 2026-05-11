<?php

declare(strict_types=1);

namespace TransferWise\Tests\Factory;

use ReflectionClass;
use TransferWise\Client;
use TransferWise\Factory\ServiceFactory;
use TransferWise\Service\Service;
use TransferWise\Tests\TestCase;

final class ServiceFactoryTest extends TestCase
{
    public function testKnownServiceReturnsSingleton(): void
    {
        $client = $this->createMock(Client::class);
        $factory = new ServiceFactory($client);
        $a = $factory->__get('profiles');
        $b = $factory->__get('profiles');
        self::assertSame($a, $b);
    }

    public function testUnknownServiceReturnsNull(): void
    {
        $client = $this->createMock(Client::class);
        $factory = new ServiceFactory($client);
        self::assertNull($factory->__get('doesNotExist'));
    }

    public function testAllRegisteredServicesInstantiate(): void
    {
        $client = $this->createMock(Client::class);
        $factory = new ServiceFactory($client);

        $ref = new ReflectionClass(ServiceFactory::class);
        $prop = $ref->getProperty('services');
        /** @var array<string, class-string> $services */
        $services = $prop->getValue(null);

        foreach (array_keys($services) as $name) {
            $instance = $factory->__get($name);
            self::assertInstanceOf(Service::class, $instance);
        }
    }
}
