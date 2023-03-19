<?php

declare(strict_types=1);

namespace Arp\LaminasFactory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Psr\Container\ContainerInterface;

interface FactoryInterface
{
    /**
     * @param array<mixed>|null $options
     *
     * @return mixed
     *
     * @throws ServiceNotFoundException
     * @throws ServiceNotCreatedException
     *
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null);
}
