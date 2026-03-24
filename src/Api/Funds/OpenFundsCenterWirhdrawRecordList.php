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

namespace KwaiShopSDK\Api\Funds;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 查询现金户提现列表
 * 更新时间: 2024-12-19 16:39:32
 * 查询商家现金户提现的记录列表
 */
final class OpenFundsCenterWirhdrawRecordList extends RpcRequest
{
    protected string $apiMethod = 'open.funds.center.wirhdraw.record.list';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
