<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory;

use Arp\LaminasFactory\ApplicationOptionsProviderTrait;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasFactory\ApplicationOptionsProviderTrait
 */
final class ApplicationOptionsProviderTraitTest extends TestCase
{
    /**
     * @var ContainerInterface&MockObject
     */
    private ContainerInterface $container;

    private string $optionsKey = 'arp';

    private string $optionsService = 'config';

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     */
    public function testWillThrowServiceNotFoundExceptionIfTheOptionsServiceCannotBeFound(): void
    {
        $subject = new class () {
            use ApplicationOptionsProviderTrait;
        };

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
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     */
    public function testWillThrowServiceNotCreatedExceptionIfTheReturnedOptionsServiceIsNotAnArray(): void
    {
        $subject = new class () {
            use ApplicationOptionsProviderTrait;
        };

        $this->container->expects($this->once())
            ->method('has')
            ->with($this->optionsService)
            ->willReturn(true);

        $this->container->expects($this->once())
            ->method('get')
            ->with($this->optionsService)
            ->willReturn(false);

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
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     */
    public function testWillThrowServiceNotCreatedExceptionIfTheReturnedOptionsServiceIsMissingOptionsKey(): void
    {
        $subject = new class () {
            use ApplicationOptionsProviderTrait;
        };

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
     * @throws ServiceNotCreatedException
     * @throws ServiceNotFoundException
     */
    public function testWillThrowServiceNotCreatedExceptionIfTheServiceOptionsAreNotOfTypeArray(): void
    {
        $subject = new class () {
            use ApplicationOptionsProviderTrait;
        };

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
