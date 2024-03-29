<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory\TestDouble;

use Arp\LaminasFactory\AbstractFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class GetServiceStdClassFactory extends AbstractFactory
{
    /**
     * @param array<mixed>|null $factoryOptions
     */
    public function __construct(private readonly mixed $dependency, array $factoryOptions = null)
    {
        parent::__construct($factoryOptions);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): \stdClass
    {
        $dependency = $this->getService($container, $this->dependency, $requestedName);

        $service = new \stdClass();
        $service->dependency = $dependency;

        return $service;
    }
}
