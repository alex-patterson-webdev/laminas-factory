<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory;

use Arp\LaminasFactory\AbstractFactory;
use Arp\LaminasFactory\FactoryInterface;
use ArpTest\LaminasFactory\Stub\StdClassFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers  \Arp\LaminasFactory\AbstractFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasFactory
 */
final class AbstractFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface&MockObject
     */
    private $container;

    /**
     * @var string
     */
    private string $serviceName = \stdClass::class;

    /**
     * @var array<mixed>|null
     */
    private ?array $options = null;

    /**
     * Prepare the test case dependencies
     */
    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * Assert that the factory implements FactoryInterface
     */
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

    /**
     * Assert the stdClass can be correctly configured and returned from __invoke()
     */
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

        $factory = new StdClassFactory();

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
}
