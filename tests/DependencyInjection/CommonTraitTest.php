<?php

namespace Tourze\UserEventBundle\Tests\DependencyInjection;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserEventBundle\DependencyInjection\CommonTrait;

class CommonTraitTest extends TestCase
{
    /**
     * 使用 CommonTrait 的测试类
     */
    private $traitUser;

    /**
     * @var ContainerBuilder&MockObject
     */
    private $container;

    protected function setUp(): void
    {
        // 创建一个使用该 trait 的匿名类
        $this->traitUser = new class {
            use CommonTrait;
        };

        $this->container = $this->createMock(ContainerBuilder::class);
    }

    public function testFetchUserInteractionEventsReturnsEmptyWhenNoBundles(): void
    {
        // 设置 kernel.bundles 参数返回空数组
        $this->container->method('getParameter')
            ->with('kernel.bundles')
            ->willReturn([]);

        $result = iterator_to_array($this->traitUser->fetchUserInteractionEvents($this->container));
        $this->assertEmpty($result);
    }

    public function testFetchUserInteractionEventsSkipsNonExistentEventDir(): void
    {
        $bundle = 'TestBundle';
        $reflection = $this->createMock(\ReflectionClass::class);
        $reflection->method('getFileName')
            ->willReturn('/path/to/bundle/TestBundle.php');

        $this->container->method('getParameter')
            ->with('kernel.bundles')
            ->willReturn([$bundle]);

        $this->container->method('getReflectionClass')
            ->with($bundle)
            ->willReturn($reflection);

        $reflection->method('getNamespaceName')
            ->willReturn('App\\Bundle');

        // 模拟 is_dir 返回 false
        $this->assertTrue(true); // 单纯的断言，因为无法模拟 is_dir

        $result = iterator_to_array($this->traitUser->fetchUserInteractionEvents($this->container));
        $this->assertEmpty($result); // 预期为空，因为目录不存在
    }

    // 注意：完整测试此 trait 需要模拟文件系统操作（is_dir、glob 等），
    // 由于 PHP 原生函数难以模拟，我们只能测试基本场景
    // 在真实环境中，可以考虑使用 vfsStream 等库来模拟文件系统
}
