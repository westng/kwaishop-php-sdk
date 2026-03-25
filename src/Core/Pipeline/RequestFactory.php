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

namespace KwaiShopSDK\Core\Pipeline;

use KwaiShopSDK\Core\Profile\Config;
use KwaiShopSDK\Core\Signing\HmacSha256Signer;
use KwaiShopSDK\Core\Signing\Md5Signer;
use KwaiShopSDK\Core\Signing\SignerInterface;
use KwaiShopSDK\Support\Clock;
use KwaiShopSDK\Support\Json;

final class RequestFactory
{
    private readonly SignerInterface $resolvedSigner;

    public function __construct(
        private readonly Config $config,
        ?SignerInterface $signer = null,
    ) {
        $this->resolvedSigner = $signer ?? (
            $this->config->signMethod() === Config::SIGN_METHOD_MD5
                ? new Md5Signer()
                : new HmacSha256Signer()
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array{url:string, params:array<string, scalar|null>}
     */
    public function build(string $method, array $params, ?string $accessToken = null, string $version = '1'): array
    {
        $signer = $this->signer();
        $publicParams = [
            'appkey' => $this->config->appKey(),
            'method' => $method,
            'version' => $version,
            'param' => Json::encode($params),
            'access_token' => $accessToken,
            'timestamp' => Clock::currentMilliseconds(),
            'signMethod' => $signer->name(),
        ];

        $publicParams['sign'] = $signer->sign($publicParams, $this->config->signSecret());

        return [
            'url' => $this->buildApiUrl($method),
            'params' => $publicParams,
        ];
    }

    private function buildApiUrl(string $method): string
    {
        return rtrim($this->config->baseUrl(), '/') . '/' . str_replace('.', '/', ltrim($method, '/'));
    }

    private function signer(): SignerInterface
    {
        return $this->resolvedSigner;
    }
}
