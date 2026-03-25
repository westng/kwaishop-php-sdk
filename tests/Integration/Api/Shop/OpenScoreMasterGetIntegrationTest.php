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

namespace KwaiShopSDK\Tests\Integration\Api\Shop;

use KwaiShopSDK\Api\Shop\OpenScoreMasterGet;
use KwaiShopSDK\Core\Profile\Config;
use KwaiShopSDK\Exception\TransportException;
use KwaiShopSDK\KwaiShopClient;
use KwaiShopSDK\Tests\Fixtures\TestConfigFactory;
use PHPUnit\Framework\TestCase;

final class OpenScoreMasterGetIntegrationTest extends TestCase
{
    public function testExecutePrintsRealApiResponse(): void
    {
        if (!TestConfigFactory::shouldRunIntegrationTests()) {
            self::markTestSkipped('Set KWAISHOP_RUN_INTEGRATION_TESTS=1 to run real integration tests.');
        }

        if (!TestConfigFactory::hasIntegrationCredentials()) {
            self::markTestSkipped(
                'Missing required integration envs: KWAISHOP_TEST_APP_KEY, KWAISHOP_TEST_APP_SECRET, '
                . 'KWAISHOP_TEST_SIGN_SECRET, KWAISHOP_TEST_ACCESS_TOKEN'
            );
        }

        $client = new KwaiShopClient(
            new Config(
                appKey: TestConfigFactory::make()->appKey(),
                appSecret: TestConfigFactory::make()->requiredAppSecret(),
                signSecret: TestConfigFactory::make()->signSecret(),
                baseUrl: TestConfigFactory::make()->baseUrl()
            )
        );

        $api = new OpenScoreMasterGet($client);
        try {
            $response = $api->execute([], TestConfigFactory::accessToken());
        } catch (TransportException $exception) {
            if (str_contains($exception->getMessage(), 'Could not resolve host')) {
                self::markTestSkipped('Network/DNS unavailable for integration test in current environment.');
            }

            throw $exception;
        }

        fwrite(STDERR, json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . PHP_EOL);

        self::assertSame(1, $response['result']);
        self::assertArrayHasKey('data', $response);
    }
}
