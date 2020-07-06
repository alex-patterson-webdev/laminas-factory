<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory\Exception;

use Arp\LaminasFactory\Exception\ServiceNotCreatedException;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasFactory\Exception
 */
final class ServiceNotCreatedExceptionTest extends TestCase
{
    /**
     * Assert that the class implements \Exception.
     *
     * @covers \Arp\LaminasFactory\Exception\ServiceNotCreatedException
     */
    public function testImplementsException(): void
    {
        $exception = new ServiceNotCreatedException('Test exception message');

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    /**
     * Assert that the class implements the laminas ServiceNotCreatedException.
     *
     * @covers \Arp\LaminasFactory\Exception\ServiceNotCreatedException
     */
    public function testImplementsLaminasServiceNotCreatedException(): void
    {
        $exception = new ServiceNotCreatedException('Test exception message');

        $this->assertInstanceOf(\Laminas\ServiceManager\Exception\ServiceNotCreatedException::class, $exception);
    }
}
