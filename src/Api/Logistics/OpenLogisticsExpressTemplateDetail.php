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

use KwaiShopSDK\Client\RpcRequest;

/**
 * 查询运费模板详情
 * 更新时间: 2024-07-16 11:41:03
 * 根据运费模板id查询运费模板，结合快手行政区划库接口open.address.district.list进行查询
 */
final class OpenLogisticsExpressTemplateDetail extends RpcRequest
{
    protected string $apiMethod = 'open.logistics.express.template.detail';

    protected string $httpMethod = 'GET';

    protected string $version = '1';

    protected string $contentType = 'application/json';
}
