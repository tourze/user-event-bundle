<?php

namespace Tourze\UserEventBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * 收集事件
 */
#[Autoconfigure(public: true)]
class EventCollector
{
    /**
     * @var array<string>
     */
    private array $eventClasses = [];

    /**
     * @return array<string>
     */
    public function getEventClasses(): array
    {
        return $this->eventClasses;
    }

    /**
     * @param array<string> $eventClasses
     */
    public function setEventClasses(array $eventClasses): void
    {
        $this->eventClasses = $eventClasses;
    }

    public function addEventClass(string $eventClass): void
    {
        $this->eventClasses[] = $eventClass;
    }
}
