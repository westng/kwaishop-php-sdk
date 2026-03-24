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
 * 查询短信发送结果
 * 更新时间: 2022-02-14 14:35:52
 * 查询短信发送结果
 */
final class OpenSmsSendResult extends RpcRequest
{
    protected string $apiMethod = 'open.sms.send.result';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
