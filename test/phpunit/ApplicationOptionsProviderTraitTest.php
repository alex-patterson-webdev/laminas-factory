<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory;

use Arp\LaminasFactory\ApplicationOptionsProviderTrait;
use Arp\LaminasFactory\Exception\ServiceNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasFactory
 */
final class ApplicationOptionsProviderTraitTest extends TestCase
{
    /**
     * @var ContainerInterface|MockObject
     */
    private $container;

    /**
     * @var string
     */
    private $optionsService;

    /**
     * Setup the test dependencies.
     */
    public function setUp(): void
    {
        $this->optionsService = 'config';

        $this->container = $this->getMockForAbstractClass(ContainerInterface::class);
    }

    /**
     * Assert that the getApplicationOptions() method will throw a ServiceNotFoundException if the configured
     * application service cannot be found within the container.
     */
    public function testWillThrowServiceNotFoundExceptionIfTheApplicationServiceCannotBeFound(): void
    {
        /** @var ApplicationOptionsProviderTrait|MockObject $subject */
        $subject = $this->getMockForTrait(ApplicationOptionsProviderTrait::class);

        $this->container->expects($this->once())
            ->method('has')
            ->with($this->optionsService)
            ->willReturn(false);

        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The required application options service \'%s\' could not be found in \'%s::%s\'.',
                $this->optionsService,
                ApplicationOptionsProviderTrait::class,
                'getApplicationOptions'
            )
        );

        $subject->getApplicationOptions($this->container);
    }
}
