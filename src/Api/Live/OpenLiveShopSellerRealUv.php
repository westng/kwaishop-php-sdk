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

namespace KwaiShopSDK\Api\Live;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 查询主播直播间的实时UV
 * 更新时间: 2022-07-22 15:29:16
 * 查询当前主播直播间的实时UV
 */
final class OpenLiveShopSellerRealUv extends RpcRequest
{
    protected string $apiMethod = 'open.live.shop.seller.real.uv';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
