# kwaishop-php-sdk

PHP SDK for Kwaishop E-commerce Open Platform.

## Current Scope

The repository currently includes the `v1.0.0` core foundation:

- configuration
- signing with `MD5` and `HMAC_SHA256`
- OAuth client
- Guzzle transport
- request factory
- response parser
- main SDK client

## Planned Usage

```php
use Kwaishop\PhpSdk\Config\Config;
use Kwaishop\PhpSdk\KwaiShopClient;

$config = new Config(
    appKey: 'your-app-key',
    appSecret: 'your-app-secret',
    signSecret: 'your-sign-secret',
);

$client = new KwaiShopClient($config);
```

## Test Environment

Test-only environment variables can be defined in `.env`.

```bash
cp .env.example .env
```

`phpunit` loads `.env` through `tests/bootstrap.php`. This is only for tests and does not affect SDK runtime usage.

Tests can read these values through [TestConfigFactory.php](/Users/west/kwaishop-php-sdk/tests/Support/TestConfigFactory.php).

## Manual OAuth Check

For local authorization flow checks, use the test-only helper script:

```bash
php tests/manual/oauth_flow.php authorize --app-type=self https://your-callback.test merchant_order,merchant_item local-test
php tests/manual/oauth_flow.php authorize --app-type=self merchant_order,merchant_item local-test
php tests/manual/oauth_flow.php authorize --app-type=service-market merchant_order,merchant_item local-test
php tests/manual/oauth_flow.php exchange YOUR_CODE
php tests/manual/oauth_flow.php refresh
php tests/manual/oauth_flow.php client-token
```

This script reads `.env` through the test bootstrap and is only intended for local verification.
If `KWAISHOP_TEST_REDIRECT_URI` is set in `.env`, the `authorize` command can omit the redirect URI argument.
Use `--app-type=self` for self-developed apps and `--app-type=service-market` for third-party service-market flows.

## Manual API Check

For direct API verification, use the raw request helper:

```bash
php tests/manual/api_call.php call open.shop.info.get '{}'
php tests/manual/api_call.php call open.seller.order.list '{"pageSize":20,"pageNum":1}' YOUR_ACCESS_TOKEN
```

If the access token argument is omitted, the script uses `KWAISHOP_TEST_ACCESS_TOKEN` from `.env`.
