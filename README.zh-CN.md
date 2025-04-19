# UserEventBundle

[![Latest Version](https://img.shields.io/packagist/v/tourze/user-event-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/user-event-bundle)
[![License](https://img.shields.io/github/license/tourze/user-event-bundle.svg?style=flat-square)](https://github.com/tourze/user-event-bundle/blob/main/LICENSE)

这是一个用于管理用户交互事件的 Symfony Bundle。它提供了一种灵活、可扩展的方式来处理用户之间的交互。

[English](README.md) | [中文](README.zh-CN.md)

## 功能特性

- 自动收集和管理用户交互事件
- 提供可扩展的事件基类
- 支持上下文信息传递
- 易于集成到现有的 Symfony 应用程序中

## 安装

```bash
composer require tourze/user-event-bundle
```

## 快速开始

在您的 `config/bundles.php` 文件中添加：

```php
<?php

return [
    // ...
    Tourze\UserEventBundle\UserEventBundle::class => ['all' => true],
];
```

在 `config/packages/tourze_user_event.yaml` 中添加配置：

```yaml
tourze_user_event:
    use_lookup: true  # 可选，默认为 true
```

## 使用方法

### 创建自定义事件

```php
<?php

namespace App\Event;

use Tourze\UserEventBundle\Event\UserInteractionEvent;

class UserMessageEvent extends UserInteractionEvent
{
    public static function getTitle(): string
    {
        return '用户消息事件';
    }
}
```

### 使用事件查找器

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

## 测试

```bash
./vendor/bin/phpunit packages/user-event-bundle/tests
```

## 许可证

该项目基于 MIT 许可证。详情请查看 [License File](LICENSE) 文件。 