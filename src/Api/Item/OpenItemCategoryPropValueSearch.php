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
 * 搜索类目属性值
 * 更新时间: 2026-02-06 15:47:55
 * 搜索类目相关的属性值信息，返回范围与店铺类型相关
 */
final class OpenItemCategoryPropValueSearch extends RpcRequest
{
    protected string $apiMethod = 'open.item.category.prop.value.search';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
