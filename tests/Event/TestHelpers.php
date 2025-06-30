<?php

namespace Tourze\UserEventBundle\Tests\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserEventBundle\Event\UserInteractionContext;

/**
 * 为测试创建的具体上下文实现类
 * @internal
 */
class TestUserInteractionContext implements UserInteractionContext
{
    private string $type = '';

    public function getContext(): array
    {
        return ['type' => $this->type];
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}

/**
 * 创建一个用户对象实现
 * @internal
 */
class TestUser implements UserInterface
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // do nothing
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}