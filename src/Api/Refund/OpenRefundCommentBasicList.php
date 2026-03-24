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

namespace KwaiShopSDK\Api\Refund;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 获取售后协商历史
 * 更新时间: 2023-08-11 16:30:03
 * 获取售后协商历史信息，包含节点、角色和时间
 */
final class OpenRefundCommentBasicList extends RpcRequest
{
    protected string $apiMethod = 'open.refund.comment.basic.list';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
