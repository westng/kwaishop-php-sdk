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
 * 设置仓库覆盖范围
 * 更新时间: 2022-09-20 17:41:09
 * 设置仓库覆盖范围
 */
final class OpenScmWarehouseSaleScopeTemplateOperation extends RpcRequest
{
    protected string $apiMethod = 'open.scm.warehouse.saleScopeTemplate.operation';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
