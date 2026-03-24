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
 * 一级团长报名招商活动
 * 更新时间: 2024-01-11 13:59:26
 * 一级团长报名招商活动
 */
final class OpenDistributionSecondActionApplyInvestmentActivity extends RpcRequest
{
    protected string $apiMethod = 'open.distribution.second.action.apply.investment.activity';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
