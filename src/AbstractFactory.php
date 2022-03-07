<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

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
     * @var array<mixed>|null
     */
    protected ?array $factoryOptions = null;

    /**
     * @param array<mixed>|null $factoryOptions
     */
    public function __construct(array $factoryOptions = null)
    {
        $this->factoryOptions = $factoryOptions;
    }

    /**
     * @param ContainerInterface $container     The dependency injection container.
     * @param mixed              $name          The name of the service to retrieved.
     * @param string             $requestedName The service that is being created.
     *
     * @return mixed
     *
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     */
    protected function getService(ContainerInterface $container, $name, string $requestedName)
    {
        // Returning non-string arguments reduces factory logic for options that may have already
        // been resolved to the required services
        if (!is_string($name)) {
            return $name;
        }

        if (!class_exists($name, true) && !$container->has($name)) {
            throw new ServiceNotFoundException(
                sprintf(
                    'The required \'%s\' dependency could not be found while creating service \'%s\'',
                    $name,
                    $requestedName
                )
            );
        }

        try {
            /** @throws \Exception */
            return $container->get($name);
        } catch (ContainerExceptionInterface $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The required \'%s\' dependency could not be created for service \'%s\'',
                    $name,
                    $requestedName
                ),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Create a new service instance using the provided $options.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param array<mixed>|null       $options
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
        } catch (\Exception $e) {
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
