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
 * 获取分销订单详情
 * 更新时间: 2024-10-17 19:38:44
 * 获取分销订单详情，未付款订单无法从该接口获取
 */
final class OpenSellerOrderCpsDetail extends RpcRequest
{
    protected string $apiMethod = 'open.seller.order.cps.detail';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
