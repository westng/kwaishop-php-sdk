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

namespace KwaiShopSDK\Api\Item;

use KwaiShopSDK\Core\RpcRequest;

/**
 * 纠错标品
 * 更新时间: 2025-06-20 17:28:15
 * 纠错标品
 */
final class OpenItemStandardCorrect extends RpcRequest
{
    protected string $apiMethod = 'open.item.standard.correct';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
