<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasFactory
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @trait ServiceOptionsProviderTrait
     */
    use ServiceOptionsProviderTrait;

    /**
     * @var array
     */
    protected array $factoryOptions = [];

    /**
     * @param array $factoryOptions
     */
    public function __construct(array $factoryOptions = [])
    {
        $this->factoryOptions = $factoryOptions;
    }

    /**
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $name          The name of the service to retrieved.
     * @param string             $requestedName The service that is being created.
     *
     * @return mixed
     *
     * @throws ServiceNotCreatedException If the requested service cannot be created.
     * @throws ServiceNotFoundException If the requested service cannot be loaded.
     */
    protected function getService(ContainerInterface $container, string $name, string $requestedName)
    {
        if ($name === $requestedName) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Encountered a circular dependency reference for service \'%s\'.',
                    $requestedName
                )
            );
        }

        if (!$container->has($name) && !class_exists($name, true)) {
            throw new ServiceNotFoundException(
                sprintf(
                    'The required \'%s\' dependency could not be found while creating service \'%s\'.',
                    $name,
                    $requestedName
                )
            );
        }

        return $container->get($name);
    }

    /**
     * Create a new service instance using the provided $options.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param array|null              $options
     * @param string                  $requestedName
     *
     * @return mixed
     *
     * @throws ServiceNotCreatedException  If the service cannot be built.
     */
    protected function buildService(
        ServiceLocatorInterface $serviceLocator,
        string $name,
        ?array $options,
        string $requestedName
    ) {
        try {
            return $serviceLocator->build($name, $options);
        } catch (\Throwable $e) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Failed to build service \'%s\' required as dependency of service \'%s\' : %s',
                    $name,
                    $requestedName,
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }
    }
}
