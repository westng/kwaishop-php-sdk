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
 * 发送物流短信
 * 更新时间: 2023-03-29 15:45:24
 * 根据物流运单号发送物流短信，需要和平台对接联调
 */
final class OpenSmsExpressSend extends RpcRequest
{
    protected string $apiMethod = 'open.sms.express.send';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
