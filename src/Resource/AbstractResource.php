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

namespace Kwaishop\PhpSdk\Resource;

use Kwaishop\PhpSdk\KwaiShopClient;

abstract class AbstractResource
{
    public function __construct(
        protected readonly KwaiShopClient $client,
    ) {
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    protected function request(string $method, array $params, ?string $accessToken = null, string $version = '1'): array
    {
        return $this->client->rawRequest($method, $params, $accessToken, $version);
    }
}
