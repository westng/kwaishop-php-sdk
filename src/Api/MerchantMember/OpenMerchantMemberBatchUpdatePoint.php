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

namespace KwaiShopSDK\Api\MerchantMember;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 更新会员积分
 * 更新时间: 2025-12-09 20:14:26
 * 更新会员积分
 */
final class OpenMerchantMemberBatchUpdatePoint extends RpcRequest
{
    protected string $apiMethod = 'open.merchant.member.batch.update.point';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
