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

namespace KwaiShopSDK\Api\Funds;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 国补审计提交发票
 * 更新时间: 2025-03-13 18:10:17
 * 国补审计提交发票
 */
final class OpenFundsSubsidyOpenApplyInvoice extends RpcRequest
{
    protected string $apiMethod = 'open.funds.subsidy.open.apply.invoice';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
