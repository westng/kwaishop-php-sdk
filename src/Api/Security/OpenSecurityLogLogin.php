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

namespace KwaiShopSDK\Api\Security;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 登陆日志上传接口
 * 更新时间: 2021-07-13 14:31:28
 * 自建账号体系登陆日志上传接口
 */
final class OpenSecurityLogLogin extends RpcRequest
{
    protected string $apiMethod = 'open.security.log.login';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
