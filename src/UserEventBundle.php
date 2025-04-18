<?php

namespace Tourze\UserEventBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\UserEventBundle\DependencyInjection\Compiler\CollectPass;
use Tourze\UserEventBundle\DependencyInjection\Compiler\RemoveUnusedServicePass;

class UserEventBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CollectPass());
        $container->addCompilerPass(new RemoveUnusedServicePass());
    }
}
