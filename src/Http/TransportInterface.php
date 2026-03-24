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

namespace Kwaishop\PhpSdk\Http;

interface TransportInterface
{
    /**
     * @param array<string, scalar|null> $formParams
     *
     * @return array{status:int, headers:array<string, mixed>, body:string}
     */
    public function sendForm(string $url, array $formParams): array;
}
