<?php

namespace Tourze\UserEventBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

/**
 * 创建一个用户对象实现
 */
class TestUser implements UserInterface
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // do nothing
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}

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
