<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Trait used to provided the application options to a factory.
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasFactory
 */
trait ApplicationOptionsProviderTrait
{
    /**
     * @var string
     */
    protected string $applicationOptionsKey = 'arp';

    /**
     * @var string
     */
    private string $applicationOptionsService = 'config';

    /**
     * Return an array of application options.
     *
     * @param ContainerInterface $container
     * @param string|null        $optionsKey
     *
     * @return array<mixed>
     *
     * @throws ServiceNotFoundException
     * @throws ServiceNotCreatedException
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
            $options = iterator_to_array($options, true);
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

    /**
     * @param string $optionsKey
     */
    public function setApplicationOptionsKey(string $optionsKey): void
    {
        $this->applicationOptionsKey = $optionsKey;
    }
}
