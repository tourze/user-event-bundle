<?php

namespace Tourze\UserEventBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\EventDispatcher\Event;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

/**
 * 减少一些不必要的服务注册
 */
class RemoveUnusedServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getServiceIds() as $serviceId) {
            $definition = $container->findDefinition($serviceId);
            if (empty($definition->getClass())) {
                continue;
            }

            try {
                if (!class_exists($definition->getClass())) {
                    continue;
                }
            } catch (\Throwable) {
                continue;
            }

            if (is_subclass_of($definition->getClass(), Event::class) && !is_subclass_of($definition->getClass(), UserInteractionEvent::class)) {
                $container->removeDefinition($serviceId);
            }
        }
    }
}
