<?php

namespace Tourze\UserEventBundle\Event;

/**
 * 上下文信息
 */
interface UserInteractionContext
{
    /**
     * @return array<string, mixed>
     */
    public function getContext(): array;
}
