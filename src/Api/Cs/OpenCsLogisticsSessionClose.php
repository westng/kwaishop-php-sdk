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

namespace KwaiShopSDK\Api\Cs;

use KwaiShopSDK\Client\RpcRequest;

/**
 * 物流会话关闭
 * 更新时间: 2024-09-25 13:04:23
 * 物流会话关闭
 */
final class OpenCsLogisticsSessionClose extends RpcRequest
{
    protected string $apiMethod = 'open.cs.logistics.session.close';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
