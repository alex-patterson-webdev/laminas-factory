<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory;

use Arp\LaminasFactory\ApplicationOptionsProviderTrait;
use Arp\LaminasFactory\Exception\ServiceNotCreatedException;
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
    private $optionsKey = 'arp';

    /**
     * @var string
     */
    private $optionsService = 'config';

    /**
     * Setup the test dependencies.
     */
    public function setUp(): void
    {
        $this->container = $this->getMockForAbstractClass(ContainerInterface::class);
    }

    /**
     * Assert that the getApplicationOptions() method will throw a ServiceNotFoundException if the configured
     * application service cannot be found within the container.
     */
    public function testWillThrowServiceNotFoundExceptionIfTheOptionsServiceCannotBeFound(): void
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

    /**
     * Assert that a ServiceNotCreatedException is thrown when the returned ApplicationOptionsService is not of
     * type array.
     */
    public function testWillThrowServiceNotCreatedExceptionIfTheReturnedOptionsServiceIsNotAnArray(): void
    {
        /** @var ApplicationOptionsProviderTrait|MockObject $subject */
        $subject = $this->getMockForTrait(ApplicationOptionsProviderTrait::class);

        $this->container->expects($this->once())
            ->method('has')
            ->with($this->optionsService)
            ->willReturn(true);

        $invalid = false; // non-array value

        $this->container->expects($this->once())
            ->method('get')
            ->with($this->optionsService)
            ->willReturn($invalid);

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The application key \'%s\' could not be found within the application options service \'%s\'.',
                $this->optionsKey,
                $this->optionsService
            )
        );

        $subject->getApplicationOptions($this->container);
    }

    /**
     * Assert that a ServiceNotCreatedException is thrown when the returned ApplicationOptionsService does not
     * container a array key matching the options key.
     */
    public function testWillThrowServiceNotCreatedExceptionIfTheReturnedOptionsServiceIsMissingOptionsKey(): void
    {
        /** @var ApplicationOptionsProviderTrait|MockObject $subject */
        $subject = $this->getMockForTrait(ApplicationOptionsProviderTrait::class);

        $this->container->expects($this->once())
            ->method('has')
            ->with($this->optionsService)
            ->willReturn(true);

        $invalid = [
            'foo' => 123,
        ];

        $this->container->expects($this->once())
            ->method('get')
            ->with($this->optionsService)
            ->willReturn($invalid);

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The application key \'%s\' could not be found within the application options service \'%s\'.',
                $this->optionsKey,
                $this->optionsService
            )
        );

        $subject->getApplicationOptions($this->container);
    }

    /**
     * Assert that a ServiceNotCreatedException is thrown when the resolved service options are not of type array.
     */
    public function testWillThrowServiceNotCreatedExceptionIfTheServiceOptionsAreNotOfTypeArray(): void
    {
        /** @var ApplicationOptionsProviderTrait|MockObject $subject */
        $subject = $this->getMockForTrait(ApplicationOptionsProviderTrait::class);

        $this->container->expects($this->once())
            ->method('has')
            ->with($this->optionsService)
            ->willReturn(true);

        $invalid = [
            $this->optionsKey => false, // invalid options type!
        ];

        $this->container->expects($this->once())
            ->method('get')
            ->with($this->optionsService)
            ->willReturn($invalid);

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The application options must be an \'array\' or object of type \'%s\'; \'%s\' provided in \'%s::%s\'.',
                \Traversable::class,
                gettype($invalid[$this->optionsKey]),
                ApplicationOptionsProviderTrait::class,
                'getApplicationOptions'
            )
        );

        $subject->getApplicationOptions($this->container);
    }
}
