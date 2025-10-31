<?php

namespace Tourze\UserEventBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\UserEventBundle\Exception\InvalidUserArgumentException;

/**
 * @internal
 */
#[CoversClass(InvalidUserArgumentException::class)]
final class InvalidUserArgumentExceptionTest extends AbstractExceptionTestCase
{
    public function testExceptionInheritance(): void
    {
        $exception = new InvalidUserArgumentException('Test message');
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertSame('Test message', $exception->getMessage());
    }

    public function testExceptionWithCode(): void
    {
        $exception = new InvalidUserArgumentException('Test message', 123);
        $this->assertSame(123, $exception->getCode());
    }

    public function testExceptionWithPrevious(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new InvalidUserArgumentException('Test message', 0, $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }
}
