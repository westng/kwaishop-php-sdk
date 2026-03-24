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

namespace KwaiShopSDK\Api\Item;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 更新在线商品价格
 * 更新时间: 2025-06-20 17:31:18
 * 更新在线商品价格
 */
final class OpenItemSkuPriceUpdate extends RpcRequest
{
    protected string $apiMethod = 'open.item.sku.price.update';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
