<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Factory\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasFactory
 */
final class AbstractFactoryTest extends TestCase
{
    /**
     * Assert that the AbstractFactory implements the FactoryInterface.
     *
     * @covers \Arp\LaminasFactory\AbstractFactory
     */
    public function testImplementsFactoryInterface(): void
    {
        /** @var AbstractFactory|MockObject $factory */
        $factory = $this->getMockForAbstractClass(AbstractFactory::class);

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }
}
