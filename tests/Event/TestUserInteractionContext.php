<?php

namespace Tourze\UserEventBundle\Tests\Event;

use Tourze\UserEventBundle\Event\UserInteractionContext;

/**
 * 为测试创建的具体上下文实现类
 *
 * @internal
 */
class TestUserInteractionContext implements UserInteractionContext
{
    private string $type = '';

    public function getContext(): array
    {
        return ['type' => $this->type];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
