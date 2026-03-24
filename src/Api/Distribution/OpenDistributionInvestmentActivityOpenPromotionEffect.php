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
 * 招商活动推广效果
 * 更新时间: 2023-08-24 14:34:19
 * 招商活动推广效果
 */
final class OpenDistributionInvestmentActivityOpenPromotionEffect extends RpcRequest
{
    protected string $apiMethod = 'open.distribution.investment.activity.open.promotion.effect';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
