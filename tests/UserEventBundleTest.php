<?php

namespace Tourze\UserEventBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserEventBundle\DependencyInjection\Compiler\CollectPass;
use Tourze\UserEventBundle\DependencyInjection\Compiler\RemoveUnusedServicePass;
use Tourze\UserEventBundle\UserEventBundle;

class UserEventBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $bundle = new UserEventBundle();

        // 使用实际的 ContainerBuilder 而非 mock
        $containerBuilder = new ContainerBuilder();

        // 执行测试
        $bundle->build($containerBuilder);

        // 验证编译器通道是否已添加
        $passes = $containerBuilder->getCompilerPassConfig()->getPasses();

        // 检查是否包含我们期望的编译器通道类型
        $foundCollectPass = false;
        $foundRemoveUnusedServicePass = false;

        foreach ($passes as $pass) {
            if ($pass instanceof CollectPass) {
                $foundCollectPass = true;
            }
            if ($pass instanceof RemoveUnusedServicePass) {
                $foundRemoveUnusedServicePass = true;
            }
        }

        $this->assertTrue($foundCollectPass, '编译器通道 CollectPass 未被添加');
        $this->assertTrue($foundRemoveUnusedServicePass, '编译器通道 RemoveUnusedServicePass 未被添加');
    }
}
