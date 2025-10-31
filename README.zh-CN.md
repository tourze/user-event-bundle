# UserEventBundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/user-event-bundle.svg?style=flat-square)]
(https://packagist.org/packages/tourze/user-event-bundle)
[![License](https://img.shields.io/github/license/tourze/user-event-bundle.svg?style=flat-square)]
(https://github.com/tourze/user-event-bundle/blob/main/LICENSE)
[![PHP Version Require](https://img.shields.io/packagist/php-v/tourze/user-event-bundle.svg?style=flat-square)]
(https://packagist.org/packages/tourze/user-event-bundle)
[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/user-event-bundle/ci.yml?branch=main&style=flat-square)]
(https://github.com/tourze/user-event-bundle/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/user-event-bundle.svg?style=flat-square)]
(https://codecov.io/gh/tourze/user-event-bundle)

这是一个用于管理用户交互事件的 Symfony Bundle。它提供了一种灵活、可扩展的方式来处理
用户之间的交互，为实现写扩散模型的消息系统提供了基础，其中事件以一对一的关系在用户之间分发。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [快速开始](#快速开始)
  - [1. Bundle 注册](#1-bundle-注册)
  - [2. 创建自定义事件](#2-创建自定义事件)
  - [3. 分发事件](#3-分发事件)
  - [4. 使用事件查找器](#4-使用事件查找器)
- [高级用法](#高级用法)
  - [自定义事件上下文](#自定义事件上下文)
  - [事件收集服务](#事件收集服务)
  - [事件监听器](#事件监听器)
- [API 参考](#api-参考)
  - [UserInteractionEvent](#userinteractionevent)
  - [EventFinder](#eventfinder)
  - [EventCollector](#eventcollector)
- [系统要求](#系统要求)
- [配置](#配置)
- [架构设计](#架构设计)
- [测试](#测试)
- [贡献](#贡献)
- [许可证](#许可证)

## 功能特性

- 🎯 **用户交互事件管理**：全面的用户间交互处理系统
- 🔧 **可扩展的事件基类**：抽象的 `UserInteractionEvent` 类用于创建自定义事件
- 📊 **事件收集与发现**：自动收集和枚举事件类
- 🔍 **事件查找器服务**：轻松选择和发现可用事件
- 🏗️ **依赖注入**：完整的 Symfony DI 容器集成
- 📝 **上下文支持**：内置的事件上下文信息处理
- 🔄 **写扩散模型**：为一对一消息分发模式而设计

## 安装

```bash
composer require tourze/user-event-bundle
```

## 快速开始

### 1. Bundle 注册

该 Bundle 会在您的 Symfony 应用程序中自动注册。

### 2. 创建自定义事件

```php
<?php

namespace App\Event;

use Tourze\UserEventBundle\Event\UserInteractionEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class UserMessageEvent extends UserInteractionEvent
{
    public static function getTitle(): string
    {
        return '用户消息事件';
    }
}
```

### 3. 分发事件

```php
<?php

namespace App\Service;

use App\Event\UserMessageEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageService
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher
    ) {}
    
    public function sendMessage(UserInterface $sender, UserInterface $receiver, string $message): void
    {
        $event = new UserMessageEvent();
        $event->setSender($sender);
        $event->setReceiver($receiver);
        $event->setMessage($message);
        
        $this->eventDispatcher->dispatch($event);
    }
}
```

### 4. 使用事件查找器

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Tourze\UserEventBundle\Service\EventFinder;

class EventController extends AbstractController
{
    #[Route('/events', name: 'app_events')]
    public function index(EventFinder $eventFinder): Response
    {
        $events = $eventFinder->genSelectData();
        
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
}
```

## 高级用法

### 自定义事件上下文

创建带有自定义上下文信息的事件：

```php
<?php

namespace App\Event;

use Tourze\UserEventBundle\Event\UserInteractionEvent;
use Tourze\UserEventBundle\Event\UserInteractionContext;

class CustomEvent extends UserInteractionEvent
{
    private array $metadata = [];

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public static function getTitle(): string
    {
        return '带元数据的自定义事件';
    }
}
```

### 事件收集服务

以编程方式处理事件集合：

```php
<?php

namespace App\Service;

use Tourze\UserEventBundle\Service\EventCollector;

class CustomEventService
{
    public function __construct(private EventCollector $eventCollector) {}

    public function getAllEventTypes(): array
    {
        return $this->eventCollector->getEventClasses();
    }

    public function registerCustomEvent(string $eventClass): void
    {
        $this->eventCollector->addEventClass($eventClass);
    }
}
```

### 事件监听器

为用户交互事件创建监听器：

```php
<?php

namespace App\EventListener;

use App\Event\UserMessageEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: UserMessageEvent::class)]
class UserMessageListener
{
    public function __invoke(UserMessageEvent $event): void
    {
        // 处理用户消息事件
        $sender = $event->getSender();
        $receiver = $event->getReceiver();
        $message = $event->getMessage();
        
        // 记录、存储或处理交互
    }
}
```

## API 参考

### UserInteractionEvent

所有用户交互事件的基类。

#### 属性
- `UserInterface $sender` - 发送事件的用户
- `UserInterface $receiver` - 接收事件的用户
- `string $message` - 消息内容

#### 方法
- `getSender(): UserInterface`
- `setSender(UserInterface $sender): void`
- `getReceiver(): UserInterface`
- `setReceiver(UserInterface $receiver): void`
- `getMessage(): string`
- `setMessage(string $message): void`
- `static getTitle(): string` - 重写以提供事件标题

### EventFinder

用于发现和选择可用事件的服务。

#### 方法
- `genSelectData(): iterable` - 返回用于选择组件的格式化事件数据

### EventCollector

用于收集和管理事件类的服务。

#### 方法
- `getEventClasses(): array` - 返回事件类名数组
- `addEventClass(string $eventClass): void` - 将事件类添加到集合中

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Symfony Security 组件
- Symfony Event Dispatcher

## 配置

该 Bundle 会自动配置。服务会在 DI 容器中注册，事件会通过编译器传递自动收集。

## 架构设计

该 Bundle 围绕消息系统的**写扩散模型**而设计：

- 事件代表一对一的用户交互
- 每个事件都有发送者和接收者
- 事件通过 Symfony 的事件系统分发
- 事件类会自动收集并可以被枚举

## 测试

运行测试套件：

```bash
./vendor/bin/phpunit packages/user-event-bundle/tests
```

运行 PHPStan 分析：

```bash
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/user-event-bundle
```

## 贡献

欢迎贡献！请随时提交 Pull Request。

## 许可证

该项目基于 MIT 许可证。详情请查看 [License File](LICENSE) 文件。 