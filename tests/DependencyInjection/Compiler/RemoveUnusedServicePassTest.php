<?php

namespace Tourze\UserEventBundle\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\EventDispatcher\Event;
use Tourze\UserEventBundle\DependencyInjection\Compiler\RemoveUnusedServicePass;
use Tourze\UserEventBundle\Event\UserInteractionEvent;

class RemoveUnusedServicePassTest extends TestCase
{
    private RemoveUnusedServicePass $removeUnusedServicePass;

    protected function setUp(): void
    {
        $this->removeUnusedServicePass = new RemoveUnusedServicePass();
    }

    public function testProcessWithNonUserInteractionEventRemoved(): void
    {
        // 创建实际的 ContainerBuilder 对象
        $container = new ContainerBuilder();

        // 创建并注册非 UserInteractionEvent 的 Event 服务（应该被移除）
        $eventMockClass = 'MockEvent_' . uniqid();
        eval("
            class $eventMockClass extends " . Event::class . " {
            }
        ");

        $container->register($eventMockClass, $eventMockClass);
        $this->assertTrue($container->has($eventMockClass), '测试前应该存在服务');

        // 执行测试
        $this->removeUnusedServicePass->process($container);

        // 验证服务已被移除
        $this->assertFalse($container->has($eventMockClass), '非 UserInteractionEvent 服务应该被移除');
    }

    public function testProcessWithUserInteractionEventNotRemoved(): void
    {
        // 创建实际的 ContainerBuilder 对象
        $container = new ContainerBuilder();

        // 创建并注册 UserInteractionEvent 子类服务（不应该被移除）
        $uiEventMockClass = 'MockUserInteractionEvent_' . uniqid();
        eval("
            class $uiEventMockClass extends " . UserInteractionEvent::class . " {
                public static function getTitle(): string {
                    return 'Test Event';
                }
            }
        ");

        $container->register($uiEventMockClass, $uiEventMockClass);
        $this->assertTrue($container->has($uiEventMockClass), '测试前应该存在服务');

        // 执行测试
        $this->removeUnusedServicePass->process($container);

        // 验证服务没有被移除
        $this->assertTrue($container->has($uiEventMockClass), 'UserInteractionEvent 子类服务不应被移除');
    }

    public function testProcessWithNonEventClassNotRemoved(): void
    {
        // 创建实际的 ContainerBuilder 对象
        $container = new ContainerBuilder();

        // 创建并注册非 Event 类的服务（不应该被移除）
        $normalClass = 'MockNormalClass_' . uniqid();
        eval("
            class $normalClass {
            }
        ");

        $container->register($normalClass, $normalClass);
        $this->assertTrue($container->has($normalClass), '测试前应该存在服务');

        // 执行测试
        $this->removeUnusedServicePass->process($container);

        // 验证服务没有被移除
        $this->assertTrue($container->has($normalClass), '非 Event 类服务不应该被移除');
    }
}
