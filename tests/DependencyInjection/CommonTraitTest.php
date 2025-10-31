<?php

namespace Tourze\UserEventBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserEventBundle\DependencyInjection\CommonTrait;

/**
 * @internal
 */
#[CoversClass(CommonTrait::class)]
final class CommonTraitTest extends TestCase
{
    private TestCommonTraitImplementation $traitUser;

    /**
     * @var ContainerBuilder&MockObject
     */
    private $container;

    protected function setUp(): void
    {
        parent::setUp();

        // 创建一个使用该 trait 的测试实现类
        $this->traitUser = new TestCommonTraitImplementation();

        // 使用 ContainerBuilder 具体类 mock 的理由：
        // 1. ContainerBuilder 是 Symfony DI 容器的核心实现类，没有合适的接口替代，它提供了完整的容器构建功能
        // 2. CommonTrait 需要测试与 ContainerBuilder 的具体交互行为，包括 getParameter、getReflectionClass 等方法
        // 3. ContainerBuilder 的方法调用是测试的关键部分，需要精确模拟其行为来验证 trait 的业务逻辑
        $this->container = $this->createMock(ContainerBuilder::class);
    }

    public function testFetchUserInteractionEventsReturnsEmptyWhenNoBundles(): void
    {
        // 设置 kernel.bundles 参数返回空数组
        $this->container->method('getParameter')
            ->with('kernel.bundles')
            ->willReturn([])
        ;

        $result = iterator_to_array($this->traitUser->fetchUserInteractionEvents($this->container));
        $this->assertEmpty($result);
    }

    public function testFetchUserInteractionEventsSkipsNonExistentEventDir(): void
    {
        $bundle = 'TestBundle';
        // 使用 ReflectionClass 具体类 mock 的理由：
        // 1. ReflectionClass 是 PHP 反射的核心类，无法用接口替代，它是 PHP 内置的反射机制实现
        // 2. 测试需要模拟反射类的文件名和命名空间获取行为，这些方法是 bundle 扫描逻辑的核心依赖
        // 3. ReflectionClass 的方法是测试 bundle 扫描逻辑的关键部分，必须精确模拟其返回值来验证扫描逻辑
        $reflection = $this->createMock(\ReflectionClass::class);
        $reflection->method('getFileName')
            ->willReturn('/path/to/bundle/TestBundle.php')
        ;

        $this->container->method('getParameter')
            ->with('kernel.bundles')
            ->willReturn([$bundle])
        ;

        $this->container->method('getReflectionClass')
            ->with($bundle)
            ->willReturn($reflection)
        ;

        $reflection->method('getNamespaceName')
            ->willReturn('App\Bundle')
        ;

        $result = iterator_to_array($this->traitUser->fetchUserInteractionEvents($this->container));
        $this->assertEmpty($result); // 预期为空，因为目录不存在
    }

    // 注意：完整测试此 trait 需要模拟文件系统操作（is_dir、glob 等），
    // 由于 PHP 原生函数难以模拟，我们只能测试基本场景
    // 在真实环境中，可以考虑使用 vfsStream 等库来模拟文件系统
}
