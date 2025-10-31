<?php

namespace Tourze\UserEventBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\UserEventBundle\Service\EventCollector;

/**
 * @internal
 */
#[CoversClass(EventCollector::class)]
#[RunTestsInSeparateProcesses]
final class EventCollectorTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testInitialStateIsEmptyArray(): void
    {
        $collector = self::getService(EventCollector::class);
        $this->assertSame([], $collector->getEventClasses());
    }

    public function testAddEventClass(): void
    {
        $collector = self::getService(EventCollector::class);
        $eventClass = 'TestEvent';
        $collector->addEventClass($eventClass);

        $this->assertContains($eventClass, $collector->getEventClasses());
        $this->assertCount(1, $collector->getEventClasses());
    }

    public function testAddMultipleEventClasses(): void
    {
        $collector = self::getService(EventCollector::class);
        $eventClass1 = 'TestEvent1';
        $eventClass2 = 'TestEvent2';

        $collector->addEventClass($eventClass1);
        $collector->addEventClass($eventClass2);

        $this->assertContains($eventClass1, $collector->getEventClasses());
        $this->assertContains($eventClass2, $collector->getEventClasses());
        $this->assertCount(2, $collector->getEventClasses());
    }

    public function testSetEventClasses(): void
    {
        $collector = self::getService(EventCollector::class);
        $eventClasses = ['TestEvent1', 'TestEvent2'];

        $collector->setEventClasses($eventClasses);

        $this->assertSame($eventClasses, $collector->getEventClasses());
    }
}
