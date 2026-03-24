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
 * 达人货架商品下架
 * 更新时间: 2024-10-11 15:29:02
 * 达人货架商品下架，支持批量方式
 */
final class OpenDistributionSelectionOffline extends RpcRequest
{
    protected string $apiMethod = 'open.distribution.selection.offline';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
