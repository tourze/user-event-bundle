<?php

namespace Tourze\UserEventBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

trait CommonTrait
{
    /**
     * @return iterable<string>
     */
    public function fetchUserInteractionEvents(ContainerBuilder $container): iterable
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (!is_array($bundles)) {
            return;
        }
        foreach ($bundles as $bundle) {
            yield from $this->getEventsFromBundle($container, $bundle);
        }
    }

    /**
     * @return iterable<string>
     */
    private function getEventsFromBundle(ContainerBuilder $container, string $bundle): iterable
    {
        $reflection = $container->getReflectionClass($bundle);
        if (null === $reflection) {
            return;
        }

        $fileName = $reflection->getFileName();
        if (false === $fileName) {
            return;
        }

        $eventDir = dirname($fileName) . '/Event';

        if (!is_dir($eventDir)) {
            return;
        }

        yield from $this->scanEventFiles($container, $eventDir, $reflection);
    }

    /**
     * @param \ReflectionClass<object> $bundleReflection
     * @return iterable<string>
     */
    private function scanEventFiles(ContainerBuilder $container, string $eventDir, \ReflectionClass $bundleReflection): iterable
    {
        $events = glob($eventDir . '/*.php');
        if (false === $events) {
            return;
        }

        foreach ($events as $eventFile) {
            $eventClass = $this->buildEventClassName($bundleReflection, $eventFile);

            if ($this->isValidUserInteractionEvent($container, $eventClass)) {
                yield $eventClass;
            }
        }
    }

    /**
     * @param \ReflectionClass<object> $bundleReflection
     */
    private function buildEventClassName(\ReflectionClass $bundleReflection, string $eventFile): string
    {
        return $bundleReflection->getNamespaceName() . '\Event\\' . basename($eventFile, '.php');
    }

    private function isValidUserInteractionEvent(ContainerBuilder $container, string $eventClass): bool
    {
        if (!class_exists($eventClass)) {
            return false;
        }

        $eventReflection = $container->getReflectionClass($eventClass);
        if (null === $eventReflection) {
            return false;
        }

        return $eventReflection->isSubclassOf(UserInteractionEvent::class);
    }
}
