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
 * 查询仓库详情
 * 更新时间: 2022-09-20 17:41:33
 * 根据外部编码查询仓库详情
 */
final class OpenScmWarehouseInfo extends RpcRequest
{
    protected string $apiMethod = 'open.scm.warehouse.info';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
