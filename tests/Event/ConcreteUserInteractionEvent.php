<?php

namespace Tourze\UserEventBundle\Tests\Event;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

/**
 * 具体的用户交互事件实现
 *
 * @internal
 */
#[Autoconfigure(public: true)]
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
