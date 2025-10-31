<?php

namespace Tourze\UserEventBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tourze\UserEventBundle\DependencyInjection\Compiler\CollectPass;
use Tourze\UserEventBundle\Service\EventCollector;

/**
 * @internal
 */
#[CoversClass(CollectPass::class)]
final class CollectPassTest extends TestCase
{
    public function testProcessAddsEventClassesToCollector(): void
    {
        // 准备测试数据
        $eventClass = 'App\Event\TestEvent';
        $events = [$eventClass];

        // 创建事件收集器定义
        $eventCollectorDef = new Definition(EventCollector::class);

        // 使用 ContainerBuilder 具体类 mock 的理由：
        // 1. ContainerBuilder 是 Symfony DI 容器的核心实现类，CompilerPass 必须与其交互
        // 2. 测试需要验证 getDefinition 方法的具体调用行为
        // 3. CollectPass 的 process 方法依赖于 ContainerBuilder 的具体实现细节
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // 配置 mock 行为: getDefinition 返回事件收集器定义
        $container->method('getDefinition')
            ->with(EventCollector::class)
            ->willReturn($eventCollectorDef)
        ;

        // 创建测试专用的 CollectPass 实例
        $collectPass = new TestCollectPass();
        $collectPass->setMockEvents($events);

        // 调用 process 方法
        $collectPass->process($container);

        // 验证方法调用
        $methodCalls = $eventCollectorDef->getMethodCalls();
        $this->assertCount(1, $methodCalls);
        $this->assertSame('addEventClass', $methodCalls[0][0]);
        $this->assertSame([$eventClass], $methodCalls[0][1]);
    }

    public function testProcessWithEmptyEvents(): void
    {
        // 创建事件收集器定义
        $eventCollectorDef = new Definition(EventCollector::class);

        // 使用 ContainerBuilder 具体类 mock 的理由：
        // 1. ContainerBuilder 是 Symfony DI 容器的核心实现类，CompilerPass 必须与其交互
        // 2. 测试需要验证 getDefinition 方法不被调用的行为
        // 3. CollectPass 的 process 方法依赖于 ContainerBuilder 的具体实现细节
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // getDefinition 不应被调用
        $container->expects($this->never())
            ->method('getDefinition')
        ;

        // 创建测试专用的 CollectPass 实例
        $collectPass = new TestCollectPass();
        $collectPass->setMockEvents([]);

        // 调用 process 方法
        $collectPass->process($container);

        // 验证事件收集器定义没有被修改（方法调用为空）
        $this->assertSame([], $eventCollectorDef->getMethodCalls());
    }

    public function testFetchUserInteractionEvents(): void
    {
        // 使用 ContainerBuilder 具体类 mock 的理由：
        // 1. fetchUserInteractionEvents 方法需要访问容器的 bundles 参数
        // 2. 需要使用 getReflectionClass 方法获取 bundle 反射信息
        // 3. CommonTrait 的实现依赖于 ContainerBuilder 的具体功能
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        // 配置空的 bundles 参数，模拟没有 bundles 的情况
        $container->method('getParameter')
            ->with('kernel.bundles')
            ->willReturn([])
        ;

        // 创建测试专用的 CollectPass 实例
        $collectPass = new TestCollectPass();

        // 调用 fetchUserInteractionEvents 方法
        $result = iterator_to_array($collectPass->fetchUserInteractionEvents($container));

        // 验证结果为空数组
        $this->assertSame([], $result);
    }
}
