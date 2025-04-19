<?php

namespace Tourze\UserEventBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tourze\UserEventBundle\DependencyInjection\Compiler\CollectPass;
use Tourze\UserEventBundle\Service\EventCollector;

class CollectPassTest extends TestCase
{
    public function testProcessAddsEventClassesToCollector(): void
    {
        // 准备测试数据
        $eventClass = 'App\\Event\\TestEvent';
        $events = [$eventClass];

        // 创建事件收集器定义
        $eventCollectorDef = new Definition(EventCollector::class);

        // 创建容器构建器 mock
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        // 配置 mock 行为: getDefinition 返回事件收集器定义
        $container->method('getDefinition')
            ->with(EventCollector::class)
            ->willReturn($eventCollectorDef);

        // 创建一个 CollectPass 实例，可以用于调用 process 方法
        $passReflection = new \ReflectionClass(CollectPass::class);
        $process = $passReflection->getMethod('process');
        $process->setAccessible(true);

        // 创建一个扩展的 CompilerPass 实现 fetchUserInteractionEvents 方法
        $collectPass = $this->getMockForAbstractClass(
            CollectPass::class,
            [],
            '',
            true,
            true,
            true,
            ['fetchUserInteractionEvents']
        );

        // 配置 fetchUserInteractionEvents 行为
        $collectPass->method('fetchUserInteractionEvents')
            ->with($container)
            ->willReturn($events);

        // 调用 process 方法
        $process->invoke($collectPass, $container);

        // 验证方法调用
        $methodCalls = $eventCollectorDef->getMethodCalls();
        $this->assertCount(1, $methodCalls);
        $this->assertSame('addEventClass', $methodCalls[0][0]);
        $this->assertSame([$eventClass], $methodCalls[0][1]);
    }

    public function testProcessWithEmptyEvents(): void
    {
        // 创建容器构建器 mock
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        // getDefinition 不应被调用
        $container->expects($this->never())
            ->method('getDefinition');

        // 创建一个 CollectPass 实例，可以用于调用 process 方法
        $passReflection = new \ReflectionClass(CollectPass::class);
        $process = $passReflection->getMethod('process');
        $process->setAccessible(true);

        // 创建一个扩展的 CompilerPass 实现 fetchUserInteractionEvents 方法
        $collectPass = $this->getMockForAbstractClass(
            CollectPass::class,
            [],
            '',
            true,
            true,
            true,
            ['fetchUserInteractionEvents']
        );

        // 配置 fetchUserInteractionEvents 返回空数组
        $collectPass->method('fetchUserInteractionEvents')
            ->with($container)
            ->willReturn([]);

        // 调用 process 方法
        $process->invoke($collectPass, $container);

        // 如果没有抛出异常，则测试通过
        $this->addToAssertionCount(1);
    }
}
