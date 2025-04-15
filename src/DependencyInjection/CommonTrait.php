<?php

namespace Tourze\UserEventBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

trait CommonTrait
{
    public function fetchUserInteractionEvents(ContainerBuilder $container): iterable
    {
        foreach ($container->getParameter('kernel.bundles') as $bundle) {
            $reflection = $container->getReflectionClass($bundle);
            $bundleDir = dirname($reflection->getFileName());

            // 扫描所有的 Event 类
            $eventDir = $bundleDir . '/Event';
            if (is_dir($eventDir)) {
                $events = glob($eventDir . '/*.php');
                foreach ($events as $event) {
                    // 获取event的完整类名，要注意 namespace
                    $eventClass = $reflection->getNamespaceName() . '\\Event\\' . basename($event, '.php');
                    if (!class_exists($eventClass)) {
                        continue;
                    }

                    // 是否继承了 \App\Tracking\Event\UserInteractionEvent
                    $eventReflection = $container->getReflectionClass($eventClass);
                    if (!$eventReflection->isSubclassOf(UserInteractionEvent::class)) {
                        continue;
                    }
                    yield $eventClass;
                }
            }
        }
    }
}
