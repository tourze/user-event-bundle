<?php

namespace Tourze\UserEventBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\UserEventBundle\DependencyInjection\CollectPass;

class UserEventBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CollectPass());
    }
}
