<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory\Stub;

use Arp\LaminasFactory\AbstractFactory;
use Psr\Container\ContainerInterface;

/**
 * Stub factory class to allow use to test the AbstractFactory, ApplicationOptionProviderTrait and ServiceOptionsTrait
 * with a concrete implementation
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasFactory\Stub
 */
class StdClassFactory extends AbstractFactory
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
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return \stdClass
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
     * @param ContainerInterface $container
     * @param string|null        $optionsKey
     *
     * @return array<mixed>
     */
    public function getApplicationOptions(ContainerInterface $container, ?string $optionsKey = null): array
    {
        return $this->applicationOptions ?? parent::getApplicationOptions($container, $optionsKey);
    }

    /**
     * @param ContainerInterface $container     The dependency injection container
     * @param string             $requestedName The name of the service being created
     * @param string|null        $key           The type of service that should be checked
     *
     * @return array<mixed>
     */
    public function getServiceOptions(ContainerInterface $container, string $requestedName, ?string $key = null): array
    {
        return $this->serviceOptions ?? parent::getServiceOptions($container, $requestedName, $key);
    }
}
