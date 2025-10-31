<?php

namespace Tourze\UserEventBundle\Tests\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Tourze\PHPUnitSymfonyUnitTest\AbstractEventTestCase;

/**
 * 测试具体实现的类
 *
 * @internal
 */
#[CoversClass(ConcreteUserInteractionEvent::class)]
final class ConcreteUserInteractionEventTest extends AbstractEventTestCase
{
    private ConcreteUserInteractionEvent $event;

    private UserInterface $sender;

    private UserInterface $receiver;

    protected function setUp(): void
    {
        parent::setUp();

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
        $this->assertInstanceOf(Event::class, $this->event);
    }
}
