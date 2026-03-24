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

namespace KwaiShopSDK\Api\Logistics;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 新增商家地址
 * 更新时间: 2023-06-07 11:16:04
 * 新增商家地址
 */
final class OpenAddressSellerCreate extends RpcRequest
{
    protected string $apiMethod = 'open.address.seller.create';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
