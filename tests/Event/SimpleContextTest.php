<?php

namespace Tourze\UserEventBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Tourze\UserEventBundle\Event\UserInteractionContext;

/**
 * 简单的上下文实现
 */
class SimpleContext implements UserInteractionContext
{
    private array $contextData = [];

    public function getContext(): array
    {
        return $this->contextData;
    }

    public function addContextData(string $key, mixed $value): void
    {
        $this->contextData[$key] = $value;
    }
}

/**
 * 测试上下文实现
 */
class SimpleContextTest extends TestCase
{
    private SimpleContext $context;

    protected function setUp(): void
    {
        $this->context = new SimpleContext();
    }

    public function testGetContextWithEmptyData(): void
    {
        $this->assertSame([], $this->context->getContext());
    }

    public function testAddContextData(): void
    {
        $key = 'test_key';
        $value = 'test_value';

        $this->context->addContextData($key, $value);

        $expected = [$key => $value];
        $this->assertSame($expected, $this->context->getContext());
    }

    public function testAddMultipleContextData(): void
    {
        $data = [
            'key1' => 'value1',
            'key2' => 2,
            'key3' => true,
        ];

        foreach ($data as $key => $value) {
            $this->context->addContextData($key, $value);
        }

        $this->assertSame($data, $this->context->getContext());
    }

    public function testImplementsInterface(): void
    {
        $this->assertInstanceOf(UserInteractionContext::class, $this->context);
    }
}
