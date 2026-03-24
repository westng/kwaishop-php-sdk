<?php

declare(strict_types=1);

/**
 * This file is part of Kwaishop PHP SDK.
 *
 * @link     https://github.com/westng/kwaishop-php-sdk
 * @document https://github.com/westng/kwaishop-php-sdk
 * @contact  westng
 * @license  https://github.com/westng/kwaishop-php-sdk/blob/main/LICENSE
 */

namespace Kwaishop\PhpSdk\Tests\Support;

use Kwaishop\PhpSdk\Tests\TestCase;

final class TestConfigFactoryTest extends TestCase
{
    public function testFactoryBuildsConfigFromEnvironment(): void
    {
        self::assertSame($_ENV['KWAISHOP_TEST_APP_KEY'] ?? 'test-app-key', TestConfigFactory::make()->appKey());
    }

    public function testFactoryExposesOptionalMessagePrivateKey(): void
    {
        $expected = $_ENV['KWAISHOP_TEST_MESSAGE_PRIVATE_KEY'] ?? null;

        self::assertSame($expected, TestConfigFactory::messagePrivateKey());
    }

    public function testFactoryExposesOptionalRedirectUri(): void
    {
        $expected = $_ENV['KWAISHOP_TEST_REDIRECT_URI'] ?? null;

        self::assertSame($expected, TestConfigFactory::redirectUri());
    }
}
