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

namespace KwaiShopSDK\Api\Dropshipping;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 代发订单电子面单批量取号
 * 更新时间: 2022-09-08 10:58:36
 * 代发订单电子面单批量取号（最多10个）
 */
final class OpenDropshippingEbillBatchGet extends RpcRequest
{
    protected string $apiMethod = 'open.dropshipping.ebill.batch.get';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
