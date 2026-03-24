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

namespace KwaiShopSDK\Api\Scm;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 绑定运单号
 * 更新时间: 2021-10-18 10:22:38
 * 绑定订单号和运单号
 */
final class OpenScmQualitySingleBind extends RpcRequest
{
    protected string $apiMethod = 'open.scm.quality.single.bind';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
