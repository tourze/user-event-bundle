<?php

namespace Tourze\UserEventBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserEventBundle\DependencyInjection\CommonTrait;
use Tourze\UserEventBundle\Service\EventCollector;

/**
 * 测试专用的 CollectPass 实现类
 *
 * @internal
 */
class TestCollectPass implements CompilerPassInterface
{
    use CommonTrait;

    /**
     * @var array<string>
     */
    private array $mockEvents = [];

    /**
     * @param array<string> $events
     */
    public function setMockEvents(array $events): void
    {
        $this->mockEvents = $events;
    }

    /**
     * @return iterable<string>
     */
    public function fetchUserInteractionEvents(ContainerBuilder $container): iterable
    {
        return $this->mockEvents;
    }

    public function process(ContainerBuilder $container): void
    {
        foreach ($this->fetchUserInteractionEvents($container) as $eventClass) {
            $container->getDefinition(EventCollector::class)->addMethodCall('addEventClass', [$eventClass]);
        }
    }
}
