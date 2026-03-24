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
 * 获取绑定pid页
 * 更新时间: 2021-06-07 18:21:02
 * 获取绑定pid页
 */
final class OpenDistributionPidBindUrl extends RpcRequest
{
    protected string $apiMethod = 'open.distribution.pid.bind.url';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
