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

A Symfony bundle for managing user interaction events with flexible and extensible features. 
It provides a foundation for implementing write-spread model messaging systems where events 
are dispatched between users in a one-to-one relationship.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
  - [1. Bundle Registration](#1-bundle-registration)
  - [2. Create Custom Events](#2-create-custom-events)
  - [3. Dispatch Events](#3-dispatch-events)
  - [4. Use Event Finder](#4-use-event-finder)
- [Advanced Usage](#advanced-usage)
  - [Custom Event Contexts](#custom-event-contexts)
  - [Event Collection Services](#event-collection-services)
  - [Event Listeners](#event-listeners)
- [API Reference](#api-reference)
  - [UserInteractionEvent](#userinteractionevent)
  - [EventFinder](#eventfinder)
  - [EventCollector](#eventcollector)
- [Requirements](#requirements)
- [Configuration](#configuration)
- [Architecture](#architecture)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Features

- 🎯 **User Interaction Event Management**: Comprehensive system for handling user-to-user interactions
- 🔧 **Extensible Event Base Class**: Abstract `UserInteractionEvent` class for creating custom events
- 📊 **Event Collection & Discovery**: Automatic collection and enumeration of event classes
- 🔍 **Event Finder Service**: Easy selection and discovery of available events
- 🏗️ **Dependency Injection**: Full Symfony DI container integration
- 📝 **Context Support**: Built-in context information handling for events
- 🔄 **Write-Spread Model**: Designed for one-to-one message distribution patterns

## Installation

```bash
composer require tourze/user-event-bundle
```

## Quick Start

### 1. Bundle Registration

The bundle is automatically registered in your Symfony application.

### 2. Create Custom Events

```php
<?php

namespace App\Event;

use Tourze\UserEventBundle\Event\UserInteractionEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class UserMessageEvent extends UserInteractionEvent
{
    public static function getTitle(): string
    {
        return 'User Message Event';
    }
}
```

### 3. Dispatch Events

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

### 4. Use Event Finder

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

## Advanced Usage

### Custom Event Contexts

Create events with custom context information:

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
        return 'Custom Event with Metadata';
    }
}
```

### Event Collection Services

Programmatically work with event collections:

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

### Event Listeners

Create listeners for user interaction events:

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
        // Handle the user message event
        $sender = $event->getSender();
        $receiver = $event->getReceiver();
        $message = $event->getMessage();
        
        // Log, store, or process the interaction
    }
}
```

## API Reference

### UserInteractionEvent

The base class for all user interaction events.

#### Properties
- `UserInterface $sender` - The user sending the event
- `UserInterface $receiver` - The user receiving the event  
- `string $message` - The message content

#### Methods
- `getSender(): UserInterface`
- `setSender(UserInterface $sender): void`
- `getReceiver(): UserInterface`
- `setReceiver(UserInterface $receiver): void`
- `getMessage(): string`
- `setMessage(string $message): void`
- `static getTitle(): string` - Override to provide event title

### EventFinder

Service for discovering and selecting available events.

#### Methods
- `genSelectData(): iterable` - Returns formatted event data for select components

### EventCollector

Service for collecting and managing event classes.

#### Methods
- `getEventClasses(): array` - Returns array of event class names
- `addEventClass(string $eventClass): void` - Adds event class to collection

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Symfony Security Component
- Symfony Event Dispatcher

## Configuration

The bundle is configured automatically. Services are registered in the DI container and 
events are collected automatically through compiler passes.

## Architecture

This bundle is designed around the **write-spread model** for messaging systems:

- Events represent one-to-one user interactions
- Each event has a sender and receiver
- Events are dispatched through Symfony's event system
- Event classes are automatically collected and can be enumerated

## Testing

Run the test suite:

```bash
./vendor/bin/phpunit packages/user-event-bundle/tests
```

Run PHPStan analysis:

```bash
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/user-event-bundle
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
