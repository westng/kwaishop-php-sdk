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
 * 修改商品视频标题
 * 更新时间: 2025-09-04 16:24:34
 * 修改商品视频标题
 */
final class OpenItemVideoCaptionEdit extends RpcRequest
{
    protected string $apiMethod = 'open.item.video.caption.edit';

    protected string $httpMethod = 'POST';

    protected string $version = '1';

    protected string $contentType = 'application/x-www-form-urlencoded';
}
