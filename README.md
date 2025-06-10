# UserEventBundle

[![Latest Version](https://img.shields.io/packagist/v/tourze/user-event-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/user-event-bundle)
[![License](https://img.shields.io/github/license/tourze/user-event-bundle.svg?style=flat-square)](https://github.com/tourze/user-event-bundle/blob/main/LICENSE)

A Symfony bundle for managing user interaction events with flexible and extensible features.

[English](README.md) | [中文](README.zh-CN.md)

## Features

- Automatic collection and management of user interaction events
- Extendable event base class
- Context information support
- Easy integration with existing Symfony applications

## Installation

```bash
composer require tourze/user-event-bundle
```

## Usage

### Creating Custom Events

```php
<?php

namespace App\Event;

use Tourze\UserEventBundle\Event\UserInteractionEvent;

class UserMessageEvent extends UserInteractionEvent
{
    public static function getTitle(): string
    {
        return 'User Message Event';
    }
}
```

### Using the Event Finder

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

## Testing

```bash
./vendor/bin/phpunit packages/user-event-bundle/tests
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
