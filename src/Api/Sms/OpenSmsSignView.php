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

namespace KwaiShopSDK\Api\Sms;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 查询短信签名
 * 更新时间: 2022-06-09 14:29:05
 * 根据短信签名内容或ID查询短信签名详情和审核状态
 */
final class OpenSmsSignView extends RpcRequest
{
    protected string $apiMethod = 'open.sms.sign.view';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
