<?php

namespace Tourze\UserEventBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EnumExtra\SelectDataFetcher;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

/**
 * 查找出所有跟用户直接相关的事件
 */
#[Autoconfigure(public: true)]
class EventFinder implements SelectDataFetcher
{
    public function __construct(private readonly EventCollector $eventCollector)
    {
    }

    public function genSelectData(): iterable
    {
        foreach ($this->eventCollector->getEventClasses() as $name) {
            /** @var UserInteractionEvent $name */
            $label = $name::getTitle() ?: $name;
            yield [
                'label' => $label,
                'text' => $label,
                'value' => $name,
                'name' => $name,
            ];
        }
    }
}
