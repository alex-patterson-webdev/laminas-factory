<?php

declare(strict_types=1);

namespace ArpTest\LaminasFactory\Exception;

use Arp\LaminasFactory\Exception\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasFactory\Exception
 */
final class ServiceNotFoundExceptionTest extends TestCase
{
    /**
     * Assert that the class implements \Exception.
     *
     * @covers \Arp\LaminasFactory\Exception\ServiceNotFoundException
     */
    public function testImplementsException(): void
    {
        $exception = new ServiceNotFoundException('Test exception message');

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    /**
     * Assert that the class implements the laminas ServiceNotFoundException.
     *
     * @covers \Arp\LaminasFactory\Exception\ServiceNotFoundException
     */
    public function testImplementsLaminasServiceNotCreatedException(): void
    {
        $exception = new ServiceNotFoundException('Test exception message');

        $this->assertInstanceOf(\Laminas\ServiceManager\Exception\ServiceNotFoundException::class, $exception);
    }
}
