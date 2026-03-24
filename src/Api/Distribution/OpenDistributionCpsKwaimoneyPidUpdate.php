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

namespace KwaiShopSDK\Api\Distribution;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 更新快赚客推广位
 * 更新时间: 2022-01-04 11:29:15
 * 更新快赚客推广位
 */
final class OpenDistributionCpsKwaimoneyPidUpdate extends RpcRequest
{
    protected string $apiMethod = 'open.distribution.cps.kwaimoney.pid.update';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
