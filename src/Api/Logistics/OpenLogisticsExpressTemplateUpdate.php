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

namespace KwaiShopSDK\Api\Logistics;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 更新运费模板
 * 更新时间: 2022-12-01 14:13:14
 * 更新运费模板
 */
final class OpenLogisticsExpressTemplateUpdate extends RpcRequest
{
    protected string $apiMethod = 'open.logistics.express.template.update';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
