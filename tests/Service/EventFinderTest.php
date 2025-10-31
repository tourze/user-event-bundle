<?php

namespace Tourze\UserEventBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\UserEventBundle\Event\UserInteractionEvent;
use Tourze\UserEventBundle\Service\EventCollector;
use Tourze\UserEventBundle\Service\EventFinder;

/**
 * @internal
 */
#[CoversClass(EventFinder::class)]
#[RunTestsInSeparateProcesses]
final class EventFinderTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testGenSelectDataWithNoEvents(): void
    {
        $eventCollector = self::getService(EventCollector::class);
        $eventFinder = self::getService(EventFinder::class);

        // 设置空事件类列表
        $eventCollector->setEventClasses([]);

        $result = iterator_to_array($eventFinder->genSelectData());
        $this->assertEmpty($result);
    }

    public function testGenSelectDataWithEvents(): void
    {
        $eventCollector = self::getService(EventCollector::class);
        $eventFinder = self::getService(EventFinder::class);

        // 定义一个临时类用于测试
        $testEvent = new class extends UserInteractionEvent {
            public static function getTitle(): string
            {
                return 'Test Event Title';
            }
        };

        $testEventClass = get_class($testEvent);

        // 设置事件类列表
        $eventCollector->setEventClasses([$testEventClass]);

        $result = iterator_to_array($eventFinder->genSelectData());

        $this->assertCount(1, $result);
        $this->assertSame('Test Event Title', $result[0]['label']);
        $this->assertSame('Test Event Title', $result[0]['text']);
        $this->assertSame($testEventClass, $result[0]['value']);
        $this->assertSame($testEventClass, $result[0]['name']);
    }

    public function testGenSelectDataWithEventHavingEmptyTitle(): void
    {
        $eventCollector = self::getService(EventCollector::class);
        $eventFinder = self::getService(EventFinder::class);

        // 创建一个没有重写getTitle方法的事件类
        $testEvent = new class extends UserInteractionEvent {
        };

        $testEventClass = get_class($testEvent);

        // 设置事件类列表
        $eventCollector->setEventClasses([$testEventClass]);

        $result = iterator_to_array($eventFinder->genSelectData());

        $this->assertCount(1, $result);
        $this->assertSame($testEventClass, $result[0]['label']); // 当title为空时，使用类名
        $this->assertSame($testEventClass, $result[0]['text']);
        $this->assertSame($testEventClass, $result[0]['value']);
        $this->assertSame($testEventClass, $result[0]['name']);
    }
}
