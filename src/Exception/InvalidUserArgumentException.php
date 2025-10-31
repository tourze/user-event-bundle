<?php

namespace Tourze\UserEventBundle\Exception;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class InvalidUserArgumentException extends \InvalidArgumentException
{
}
