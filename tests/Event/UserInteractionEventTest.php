<?php

namespace Tourze\UserEventBundle\Tests\Event;

require_once __DIR__ . '/TestHelpers.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

class UserInteractionEventTest extends TestCase
{
    private UserInteractionEvent $event;
    private UserInterface $sender;
    private UserInterface $receiver;

    protected function setUp(): void
    {
        // 创建一个匿名子类的实例，因为UserInteractionEvent是抽象类
        $this->event = new class extends UserInteractionEvent {
        };

        // 创建实际的User对象而非模拟对象
        $this->sender = new TestUser('sender');
        $this->receiver = new TestUser('receiver');
    }

    public function testGetTitle(): void
    {
        // 测试默认的getTitle方法
        $this->assertSame('', $this->event::getTitle());
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
        $message = 'Test message';
        $this->event->setMessage($message);
        $this->assertSame($message, $this->event->getMessage());
    }
}
