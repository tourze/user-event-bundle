<?php

namespace Tourze\UserEventBundle\Service;

/**
 * 收集事件
 */
class EventCollector
{
    private array $eventClasses = [];

    public function getEventClasses(): array
    {
        return $this->eventClasses;
    }

    public function setEventClasses(array $eventClasses): void
    {
        $this->eventClasses = $eventClasses;
    }

    public function addEventClass(string $eventClass): void
    {
        $this->eventClasses[] = $eventClass;
    }
}
