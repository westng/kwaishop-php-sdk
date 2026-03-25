<?php

declare(strict_types=1);

/**
 * This file is part of KwaiShopSDK.
 *
 * @link     https://github.com/westng/kwaishop-php-sdk
 * @document https://github.com/westng/kwaishop-php-sdk
 * @contact  westng
 * @license  https://github.com/westng/kwaishop-php-sdk/blob/main/LICENSE
 */

namespace KwaiShopSDK\Core;

final class PendingRequest
{
    /**
     * @param array<string, mixed> $params
     */
    public function __construct(
        private readonly RpcRequest $request,
        private array $params = [],
        private ?string $accessToken = null,
    ) {
    }

    /**
     * @param array<string, mixed> $params
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function mergeParams(array $params): self
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function send(): array
    {
        return $this->request->execute($this->params, $this->accessToken);
    }
}
