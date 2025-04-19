<?php

namespace Tourze\UserEventBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Tourze\UserEventBundle\Event\UserInteractionEvent;
use Tourze\UserEventBundle\Service\EventCollector;
use Tourze\UserEventBundle\Service\EventFinder;

class EventFinderTest extends TestCase
{
    private EventCollector $eventCollector;
    private EventFinder $eventFinder;

    protected function setUp(): void
    {
        // 使用真实实例而非 mock
        $this->eventCollector = new EventCollector();
        $this->eventFinder = new EventFinder($this->eventCollector);
    }

    public function testGenSelectDataWithNoEvents(): void
    {
        // 设置空事件类列表
        $this->eventCollector->setEventClasses([]);

        $result = iterator_to_array($this->eventFinder->genSelectData());
        $this->assertEmpty($result);
    }

    public function testGenSelectDataWithEvents(): void
    {
        // 定义一个临时类用于测试
        $testEvent = new class extends UserInteractionEvent {
            public static function getTitle(): string
            {
                return 'Test Event Title';
            }
        };

        $testEventClass = get_class($testEvent);

        // 设置事件类列表
        $this->eventCollector->setEventClasses([$testEventClass]);

        $result = iterator_to_array($this->eventFinder->genSelectData());

        $this->assertCount(1, $result);
        $this->assertSame('Test Event Title', $result[0]['label']);
        $this->assertSame('Test Event Title', $result[0]['text']);
        $this->assertSame($testEventClass, $result[0]['value']);
        $this->assertSame($testEventClass, $result[0]['name']);
    }

    public function testGenSelectDataWithEventHavingEmptyTitle(): void
    {
        // 创建一个没有重写getTitle方法的事件类
        $testEvent = new class extends UserInteractionEvent {
        };

        $testEventClass = get_class($testEvent);

        // 设置事件类列表
        $this->eventCollector->setEventClasses([$testEventClass]);

        $result = iterator_to_array($this->eventFinder->genSelectData());

        $this->assertCount(1, $result);
        $this->assertSame($testEventClass, $result[0]['label']); // 当title为空时，使用类名
        $this->assertSame($testEventClass, $result[0]['text']);
        $this->assertSame($testEventClass, $result[0]['value']);
        $this->assertSame($testEventClass, $result[0]['name']);
    }
}
