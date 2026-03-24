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
 * 删除短信模板
 * 更新时间: 2022-02-14 14:55:36
 * 删除短信模板
 */
final class OpenSmsTemplateDelete extends RpcRequest
{
    protected string $apiMethod = 'open.sms.template.delete';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
