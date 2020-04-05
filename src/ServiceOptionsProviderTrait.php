<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Arp\LaminasFactory\Exception\ServiceNotCreatedException;
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

        if (null !== $key) {
            $this->setServiceOptionsKey($key);
        }

        $key = $this->serviceOptionsKey;

        if (!array_key_exists($key, $applicationOptions)) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Unable to find a configuration key for service of type \'%s\' while creating service \'%s\'.',
                    $key,
                    $requestedName
                )
            );
        }

        $serviceOptions = (isset($this->factoryOptions) && is_array($this->factoryOptions))
            ? $this->factoryOptions
            : [];

        if (isset($applicationOptions[$key][$requestedName])) {
            if (!is_array($applicationOptions[$key][$requestedName])) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'The configuration options must be of type \'array\'; \'%s\' provided for service \'%s\'.',
                        gettype($applicationOptions[$key][$requestedName]),
                        $requestedName
                    )
                );
            }
            $serviceOptions = array_replace_recursive($serviceOptions, $applicationOptions[$key][$requestedName]);
        }

        return $serviceOptions;
    }

    /**
     * Set the key used to load the service options.
     *
     * @param string $serviceOptionsKey
     */
    public function setServiceOptionsKey(string $serviceOptionsKey): void
    {
        $this->serviceOptionsKey = $serviceOptionsKey;
    }
}
