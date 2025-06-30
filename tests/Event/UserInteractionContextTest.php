<?php

namespace Tourze\UserEventBundle\Tests\Event;

require_once __DIR__ . '/TestHelpers.php';

use PHPUnit\Framework\TestCase;
use Tourze\UserEventBundle\Event\UserInteractionContext;

class UserInteractionContextTest extends TestCase
{
    private TestUserInteractionContext $context;

    protected function setUp(): void
    {
        $this->context = new TestUserInteractionContext();
    }

    public function testType(): void
    {
        $type = 'test_type';
        $this->context->setType($type);
        $this->assertSame($type, $this->context->getType());
    }

    public function testDefaultType(): void
    {
        // 确保没有设置类型时返回空字符串
        $this->assertSame('', $this->context->getType());
    }

    public function testGetContext(): void
    {
        $type = 'test_context_type';
        $this->context->setType($type);
        $expected = ['type' => $type];
        $this->assertSame($expected, $this->context->getContext());
    }

    public function testImplementsInterface(): void
    {
        $this->assertInstanceOf(UserInteractionContext::class, $this->context);
    }
}
