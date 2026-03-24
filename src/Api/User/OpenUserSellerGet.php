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

namespace KwaiShopSDK\Api\User;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 获取商家信息
 * 更新时间: 2021-07-01 17:57:35
 * 获取商家信息api
 */
final class OpenUserSellerGet extends RpcRequest
{
    protected string $apiMethod = 'open.user.seller.get';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
