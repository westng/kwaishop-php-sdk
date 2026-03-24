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

namespace Kwaishop\PhpSdk\Support;

final class Clock
{
    public static function currentMilliseconds(): string
    {
        return (string) (int) floor(microtime(true) * 1000);
    }
}
