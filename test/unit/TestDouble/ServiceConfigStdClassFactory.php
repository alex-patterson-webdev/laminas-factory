<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory\TestDouble;

use Arp\LaminasFactory\AbstractFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

class ServiceConfigStdClassFactory extends AbstractFactory
{
    /**
     * @var array<mixed>|null
     */
    private ?array $applicationOptions;

    /**
     * @var array<mixed>|null
     */
    private ?array $serviceOptions;

    /**
     * @param array<mixed>|null $factoryOptions
     * @param array<mixed>|null $applicationOptions
     * @param array<mixed>|null $serviceOptions
     */
    public function __construct(
        ?array $factoryOptions = null,
        ?array $applicationOptions = null,
        ?array $serviceOptions = null
    ) {
        $this->applicationOptions = $applicationOptions;
        $this->serviceOptions = $serviceOptions;

        parent::__construct($factoryOptions);
    }

    /**
     * @param array<mixed>|null $options
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): \stdClass {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $object = new \stdClass();
        $object->options = $options;

        return $object;
    }

    /**
     * @return array<mixed>
     * @throws ContainerExceptionInterface
     */
    public function getApplicationOptions(ContainerInterface $container, ?string $optionsKey = null): array
    {
        return $this->applicationOptions ?? parent::getApplicationOptions($container, $optionsKey);
    }

    /**
     * @return array<mixed>
     * @throws ContainerExceptionInterface
     */
    public function getServiceOptions(ContainerInterface $container, string $requestedName, ?string $key = null): array
    {
        return $this->serviceOptions ?? parent::getServiceOptions($container, $requestedName, $key);
    }
}
