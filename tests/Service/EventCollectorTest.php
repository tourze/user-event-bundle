<?php

namespace Tourze\UserEventBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Tourze\UserEventBundle\Service\EventCollector;

class EventCollectorTest extends TestCase
{
    private EventCollector $collector;

    protected function setUp(): void
    {
        $this->collector = new EventCollector();
    }

    public function testInitialStateIsEmptyArray(): void
    {
        $this->assertSame([], $this->collector->getEventClasses());
    }

    public function testAddEventClass(): void
    {
        $eventClass = 'TestEvent';
        $this->collector->addEventClass($eventClass);

        $this->assertContains($eventClass, $this->collector->getEventClasses());
        $this->assertCount(1, $this->collector->getEventClasses());
    }

    public function testAddMultipleEventClasses(): void
    {
        $eventClass1 = 'TestEvent1';
        $eventClass2 = 'TestEvent2';

        $this->collector->addEventClass($eventClass1);
        $this->collector->addEventClass($eventClass2);

        $this->assertContains($eventClass1, $this->collector->getEventClasses());
        $this->assertContains($eventClass2, $this->collector->getEventClasses());
        $this->assertCount(2, $this->collector->getEventClasses());
    }

    public function testSetEventClasses(): void
    {
        $eventClasses = ['TestEvent1', 'TestEvent2'];

        $this->collector->setEventClasses($eventClasses);

        $this->assertSame($eventClasses, $this->collector->getEventClasses());
    }
}
