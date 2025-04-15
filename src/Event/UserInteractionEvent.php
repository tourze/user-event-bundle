<?php

namespace Tourze\UserEventBundle\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * 用户交互事件
 *
 * 在这里我们不关心持久化。我们只关心消息是怎么分发出去还有怎么处理和消费
 * 这里的消息，发送者和接受者必然是一对一的，即使群发也是一对一
 * 之所以这样，是因为到时候我们需要实现的是写扩散模型
 *
 * @see http://www.52im.net/thread-3631-1-1.html
 */
abstract class UserInteractionEvent extends Event
{
    /**
     * @var UserInterface 发送人
     */
    private UserInterface $sender;

    /**
     * @var UserInterface 接收人
     */
    private UserInterface $receiver;

    /**
     * @var string 消息内容
     */
    private string $message = '';

    //    /**
    //     * 声明这个事件的描述性标题
    //     */
    //    abstract public static function getTitle(): string;
    public static function getTitle(): string
    {
        return '';
    }

    public function getSender(): UserInterface
    {
        return $this->sender;
    }

    public function setSender(UserInterface $sender): void
    {
        $this->sender = $sender;
    }

    public function getReceiver(): UserInterface
    {
        return $this->receiver;
    }

    public function setReceiver(UserInterface $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
