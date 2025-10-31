<?php

namespace Tourze\UserEventBundle\Tests\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserEventBundle\Exception\InvalidUserArgumentException;

/**
 * 创建一个用户对象实现
 *
 * @internal
 */
class TestUser implements UserInterface
{
    /** @var non-empty-string */
    private string $username;

    public function __construct(string $username)
    {
        if ('' === $username) {
            throw new InvalidUserArgumentException('Username cannot be empty');
        }
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

    /**
     * @return non-empty-string
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
