<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory\TestDouble;

use Arp\LaminasFactory\AbstractFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class GetServiceStdClassFactory extends AbstractFactory
{
    /**
     * @var mixed
     */
    private $dependency;

    /**
     * @param mixed $dependency
     */
    public function __construct($dependency)
    {
        $this->dependency = $dependency;

        parent::__construct();
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
