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
 * 商家已报名的团长招商活动列表
 * 更新时间: 2024-06-17 14:30:03
 * 商家已报名的团长招商活动列表
 */
final class OpenDistributionSellerActivityApplyList extends RpcRequest
{
    protected string $apiMethod = 'open.distribution.seller.activity.apply.list';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
