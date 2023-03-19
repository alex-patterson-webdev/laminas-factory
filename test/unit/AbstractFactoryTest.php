<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory;

use Arp\LaminasFactory\AbstractFactory;
use Arp\LaminasFactory\FactoryInterface;
use ArpTest\LaminasFactory\TestDouble\GetServiceStdClassFactory;
use ArpTest\LaminasFactory\TestDouble\ServiceConfigStdClassFactory;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasFactory\AbstractFactory
 */
final class AbstractFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface&MockObject
     */
    private ContainerInterface $container;

    private string $serviceName = \stdClass::class;

    /**
     * @var array<mixed>|null
     */
    private ?array $options = null;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testImplementsFactoryInterface(): void
    {
        $factory = new class () extends AbstractFactory {
            /**
             * @param ContainerInterface $container
             * @param string             $requestedName
             * @param array<mixed>|null  $options
             *
             * @return \stdClass
             */
            public function __invoke(
                ContainerInterface $container,
                string $requestedName,
                array $options = null
            ): \stdClass {
                return new \stdClass();
            }
        };

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    public function testInvokeWillReturnConfiguredStdClass(): void
    {
        $applicationOptionsService = 'config';
        $applicationOptions = [
            'arp' => [
                'services' => [
                    \stdClass::class => [
                        'foo' => 'bar',
                        123 => true,
                        'hello' => [
                            'clown' => 'world',
                        ],
                    ]
                ]
            ],
        ];

        $factory = new ServiceConfigStdClassFactory();

        $this->container->expects($this->once())
            ->method('has')
            ->with($applicationOptionsService)
            ->willReturn(true);

        $this->container->expects($this->once())
            ->method('get')
            ->with($applicationOptionsService)
            ->willReturn($applicationOptions);

        $stdObject = $factory($this->container, $this->serviceName, $this->options);

        $this->assertSame($applicationOptions['arp']['services'][\stdClass::class], $stdObject->options);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testGetServiceWillReturnServiceNameWhenProvidedNonStringValue(): void
    {
        $dependency = new \stdClass();

        $factory = new GetServiceStdClassFactory($dependency);

        $createdService = $factory($this->container, 'foo');

        $this->assertSame($createdService->dependency, $dependency);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testGetServiceWillThrowServiceNotCreatedExceptionIfTheProvidedServiceIsNotAValidService(): void
    {
        $requestedName = 'FooService';
        $dependencyName = 'BarService';

        $factory = new GetServiceStdClassFactory($dependencyName);

        $this->container->expects($this->once())
            ->method('has')
            ->with($dependencyName)
            ->willReturn(false);

        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The required \'%s\' dependency could not be found while creating service \'%s\'',
                $dependencyName,
                $requestedName
            )
        );

        $factory($this->container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testGetServiceWillReThrowContainerContainerExceptionInterfaceErrors(): void
    {
        $requestedName = 'FooService';
        $dependencyName = \stdClass::class;

        $factory = new GetServiceStdClassFactory($dependencyName);

        /** @var ContainerException&MockObject $exception */
        $exception = $this->getMockForAbstractClass(ContainerException::class);

        $this->container->expects($this->once())
            ->method('get')
            ->with($dependencyName)
            ->willThrowException($exception);

        $this->expectException(ContainerException::class);

        $factory($this->container, $requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testGetServiceWillReThrowExceptionErrors(): void
    {
        $requestedName = 'FooService';
        $dependencyName = \stdClass::class;

        $factory = new GetServiceStdClassFactory($dependencyName);

        $exceptionCode = 777;
        /** @var ContainerException&MockObject $exception */
        $exception = new \Exception(
            'This is a test exception for test ' . __FUNCTION__,
            $exceptionCode
        );

        $this->container->expects($this->once())
            ->method('get')
            ->with($dependencyName)
            ->willThrowException($exception);

        $this->expectException(\Exception::class);
        $this->expectExceptionCode($exceptionCode);
        $this->expectExceptionMessage(
            sprintf(
                'The required \'%s\' dependency could not be created for service \'%s\'',
                $dependencyName,
                $requestedName
            )
        );

        $factory($this->container, $requestedName);
    }
}
