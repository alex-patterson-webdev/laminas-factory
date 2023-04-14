<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory\TestDouble;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class BuildServiceStdClassFactory extends AbstractFactory
{
    /**
     * @param array<mixed>|null $factoryOptions
     */
    public function __construct(private readonly mixed $dependency, array $factoryOptions = null)
    {
        parent::__construct($factoryOptions);
    }

    /**
     * @param ContainerInterface&ServiceLocatorInterface $container
     *
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): \StdClass
    {
        $dependency = $this->buildService($container, $this->dependency, $options, $requestedName);

        $service = new \stdClass();
        $service->dependency = $dependency;

        return $service;
    }
}
