<?php

namespace Tourze\UserEventBundle\Tests\Event;

use Tourze\UserEventBundle\Event\UserInteractionContext;

/**
 * 简单的上下文实现
 *
 * @internal
 */
class SimpleContext implements UserInteractionContext
{
    /**
     * @var array<string, mixed>
     */
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
