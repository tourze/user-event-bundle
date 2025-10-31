<?php

namespace Tourze\UserEventBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

#[Autoconfigure(public: true)]
class UserEventExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }
}
