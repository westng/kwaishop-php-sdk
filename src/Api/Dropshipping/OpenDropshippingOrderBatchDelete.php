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
 * 商家批量删除代发订单
 * 更新时间: 2022-11-30 20:07:49
 * 【商家端】商家批量删除代发订单
 */
final class OpenDropshippingOrderBatchDelete extends RpcRequest
{
    protected string $apiMethod = 'open.dropshipping.order.batch.delete';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
