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

namespace KwaiShopSDK\Tests\Unit;

use KwaiShopSDK\Config\Config;
use KwaiShopSDK\Exception\ValidationException;
use KwaiShopSDK\Client\KwaiShopClient;
use KwaiShopSDK\Tests\Mock\FakeTransport;
use PHPUnit\Framework\TestCase;

final class KwaiShopClientDynamicApiTest extends TestCase
{
    public function testClientCanBeConstructedWithDirectCredentials(): void
    {
        $transport = new FakeTransport();
        $client = KwaiShopClient::make(
            'test-app-key',
            'test-app-secret',
            'test-sign-secret',
            'default-token',
            null,
            $transport
        );

        $response = $client->OpenShopInfoGet()->send();

        self::assertSame(1, $response['result']);
        self::assertSame('default-token', $transport->requests[0]['options']['query']['access_token']);
    }

    public function testDynamicApiMethodUsesDefaultAccessTokenFromConfig(): void
    {
        $transport = new FakeTransport();
        $client = new KwaiShopClient($this->makeConfig('default-token'), $transport);

        $response = $client->OpenShopInfoGet()->send();

        self::assertSame(1, $response['result']);
        self::assertSame('GET', $transport->requests[0]['method']);
        self::assertSame('open.shop.info.get', $transport->requests[0]['options']['query']['method']);
        self::assertSame('default-token', $transport->requests[0]['options']['query']['access_token']);
    }

    public function testDynamicApiMethodSupportsParamsAndExplicitAccessTokenOverride(): void
    {
        $transport = new FakeTransport();
        $client = new KwaiShopClient($this->makeConfig('default-token'), $transport);

        $client->OpenOrderDetail()
            ->setParams(['oid' => 'OID-10001'])
            ->setAccessToken('override-token')
            ->send();

        self::assertSame('GET', $transport->requests[0]['method']);
        self::assertSame('override-token', $transport->requests[0]['options']['query']['access_token']);
        self::assertSame('open.order.detail', $transport->requests[0]['options']['query']['method']);
        self::assertStringContainsString('"oid":"OID-10001"', (string) $transport->requests[0]['options']['query']['param']);
    }

    public function testDynamicApiMethodRejectsUnknownEndpoint(): void
    {
        $client = new KwaiShopClient($this->makeConfig(), new FakeTransport());

        $this->expectException(ValidationException::class);
        $client->OpenNotExistsApi()->send();
    }

    public function testDynamicApiMethodRejectsUnexpectedArguments(): void
    {
        $client = new KwaiShopClient($this->makeConfig(), new FakeTransport());

        $this->expectException(ValidationException::class);
        $client->OpenShopInfoGet('unexpected');
    }

    private function makeConfig(?string $accessToken = null): Config
    {
        return new Config(
            appKey: 'test-app-key',
            appSecret: 'test-app-secret',
            signSecret: 'test-sign-secret',
            accessToken: $accessToken,
        );
    }
}
