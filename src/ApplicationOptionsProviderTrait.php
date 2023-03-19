<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

trait ApplicationOptionsProviderTrait
{
    protected string $applicationOptionsKey = 'arp';

    private string $applicationOptionsService = 'config';

    /**
     * @return array<mixed>
     *
     * @throws ServiceNotFoundException
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function getApplicationOptions(ContainerInterface $container, ?string $optionsKey = null): array
    {
        if (null !== $optionsKey) {
            $this->setApplicationOptionsKey($optionsKey);
        }

        if (!$container->has($this->applicationOptionsService)) {
            throw new ServiceNotFoundException(
                sprintf(
                    'The required application options service \'%s\' could not be found in \'%s\'.',
                    $this->applicationOptionsService,
                    __METHOD__
                )
            );
        }

        $options = $container->get($this->applicationOptionsService);

        if (!is_array($options) || !array_key_exists($this->applicationOptionsKey, $options)) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The application key \'%s\' could not be found within the application options service \'%s\'.',
                    $this->applicationOptionsKey,
                    $this->applicationOptionsService
                )
            );
        }

        $options = $options[$this->applicationOptionsKey];

        if ($options instanceof \Traversable) {
            $options = iterator_to_array($options);
        }

        if (!is_array($options)) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The application options must be an \'array\' or object of type \'%s\'; \'%s\' provided in \'%s\'.',
                    \Traversable::class,
                    gettype($options),
                    __METHOD__
                )
            );
        }

        return $options;
    }

    public function setApplicationOptionsKey(string $optionsKey): void
    {
        $this->applicationOptionsKey = $optionsKey;
    }
}
