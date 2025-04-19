<?php

namespace Tourze\UserEventBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

/**
 * 具体的用户交互事件实现
 */
class ConcreteUserInteractionEvent extends UserInteractionEvent
{
    private string $extraField = '';

    public static function getTitle(): string
    {
        return '测试事件';
    }

    public function getExtraField(): string
    {
        return $this->extraField;
    }

    public function setExtraField(string $value): void
    {
        $this->extraField = $value;
    }
}

/**
 * 测试具体实现的类
 */
class ConcreteUserInteractionEventTest extends TestCase
{
    private ConcreteUserInteractionEvent $event;
    private UserInterface $sender;
    private UserInterface $receiver;

    protected function setUp(): void
    {
        $this->event = new ConcreteUserInteractionEvent();

        // 使用 TestUser 类而非 mock
        $this->sender = new TestUser('sender');
        $this->receiver = new TestUser('receiver');
    }

    public function testGetTitle(): void
    {
        $this->assertSame('测试事件', ConcreteUserInteractionEvent::getTitle());
    }

    public function testSenderGetterAndSetter(): void
    {
        $this->event->setSender($this->sender);
        $this->assertSame($this->sender, $this->event->getSender());
    }

    public function testReceiverGetterAndSetter(): void
    {
        $this->event->setReceiver($this->receiver);
        $this->assertSame($this->receiver, $this->event->getReceiver());
    }

    public function testMessageGetterAndSetter(): void
    {
        $message = '测试消息';
        $this->event->setMessage($message);
        $this->assertSame($message, $this->event->getMessage());
    }

    public function testExtraFieldGetterAndSetter(): void
    {
        $value = '额外数据';
        $this->event->setExtraField($value);
        $this->assertSame($value, $this->event->getExtraField());
    }

    public function testEventIsSymfonyEvent(): void
    {
        $this->assertInstanceOf(\Symfony\Contracts\EventDispatcher\Event::class, $this->event);
    }
} 