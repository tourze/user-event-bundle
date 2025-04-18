<?php

namespace Tourze\UserEventBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserEventBundle\DependencyInjection\CommonTrait;
use Tourze\UserEventBundle\Service\EventCollector;

/**
 * 扫描所有 Bundle 下的 Event目录，收集事件
 */
class CollectPass implements CompilerPassInterface
{
    use CommonTrait;

    public function process(ContainerBuilder $container): void
    {
        foreach ($this->fetchUserInteractionEvents($container) as $eventClass) {
            $container->getDefinition(EventCollector::class)->addMethodCall('addEventClass', [$eventClass]);
        }
    }
}
