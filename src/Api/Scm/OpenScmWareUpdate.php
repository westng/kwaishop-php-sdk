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
 * 更新货品
 * 更新时间: 2022-09-21 15:13:57
 * 根据外部编码更新货品
 */
final class OpenScmWareUpdate extends RpcRequest
{
    protected string $apiMethod = 'open.scm.ware.update';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
