<?php

declare(strict_types=1);

namespace Tourze\UserEventBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\UserEventBundle\UserEventBundle;

/**
 * @internal
 */
#[CoversClass(UserEventBundle::class)]
#[RunTestsInSeparateProcesses]
final class UserEventBundleTest extends AbstractBundleTestCase
{
}
