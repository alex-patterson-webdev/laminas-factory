<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

trait ServiceOptionsProviderTrait
{
    use ApplicationOptionsProviderTrait;

    private string $serviceOptionsKey = 'services';

    /**
     * @return array<mixed>
     *
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     * @throws ContainerExceptionInterface
     */
    public function getServiceOptions(ContainerInterface $container, string $requestedName, ?string $key = null): array
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

    private function getServiceOptionsKey(?string $key): string
    {
        return $key ?? $this->serviceOptionsKey;
    }
}
