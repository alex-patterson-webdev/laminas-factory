<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractFactory implements FactoryInterface
{
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
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     * @throws ContainerExceptionInterface
     */
    protected function getService(ContainerInterface $container, mixed $name, string $requestedName): mixed
    {
        // Returning non-string arguments reduces factory logic for options that may have already been resolved
        if (!is_string($name)) {
            return $name;
        }

        if (!class_exists($name) && !$container->has($name)) {
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
     * @param array<mixed>|null $options
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    protected function buildService(
        ServiceLocatorInterface $serviceLocator,
        string $name,
        ?array $options,
        string $requestedName
    ): mixed {
        try {
            return $serviceLocator->build($name, $options);
        } catch (\Exception $e) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Failed to build service \'%s\' required as dependency of service \'%s\'',
                    $name,
                    $requestedName
                ),
                $e->getCode(),
                $e
            );
        }
    }
}
