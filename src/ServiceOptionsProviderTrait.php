<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Arp\LaminasFactory\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;

/**
 * Trait used to provide service options to factories.
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasFactory
 */
trait ServiceOptionsProviderTrait
{
    /**
     * @trait ApplicationOptionsProviderTrait
     */
    use ApplicationOptionsProviderTrait;

    /**
     * @var string
     */
    private $serviceOptionsKey = 'services';

    /**
     * @param ContainerInterface $container     The dependency injection container.
     * @param string             $requestedName The name of the service being created.
     * @param string             $key           The type of service that should be checked.
     *
     * @return array
     *
     * @throws ServiceNotCreatedException  If the service options cannot be loaded or are invalid.
     */
    public function getServiceOptions(ContainerInterface $container, string $requestedName, $key = null): array
    {
        $applicationOptions = $this->getApplicationOptions($container);
        $serviceOptionsKey = $this->getServiceOptionsKey($key);

        if (!array_key_exists($serviceOptionsKey, $applicationOptions)) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Unable to find a configuration key for service of type \'%s\' while creating service \'%s\'.',
                    $serviceOptionsKey,
                    $requestedName
                )
            );
        }

        $serviceOptions = (isset($this->factoryOptions) && is_array($this->factoryOptions))
            ? $this->factoryOptions
            : [];

        if (isset($applicationOptions[$serviceOptionsKey][$requestedName])) {
            if (!is_array($applicationOptions[$serviceOptionsKey][$requestedName])) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'The configuration options must be of type \'array\'; \'%s\' provided for service \'%s\'.',
                        gettype($applicationOptions[$serviceOptionsKey][$requestedName]),
                        $requestedName
                    )
                );
            }
            $serviceOptions = array_replace_recursive(
                $serviceOptions,
                $applicationOptions[$serviceOptionsKey][$requestedName]
            );
        }

        return $serviceOptions;
    }

    /**
     * @param string|null $key
     *
     * @return string
     */
    private function getServiceOptionsKey(?string $key): string
    {
        if (null === $key) {
            return $this->serviceOptionsKey;
        }

        return $key;
    }
}
