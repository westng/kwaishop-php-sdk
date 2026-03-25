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

namespace KwaiShopSDK\Exception;

use RuntimeException;

class KwaiShopException extends RuntimeException
{
    /** Create a base SDK exception with optional raw response context. */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        private readonly ?string $rawResponseBody = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /** Get the raw response body associated with the exception, when available. */
    public function rawResponseBody(): ?string
    {
        return $this->rawResponseBody;
    }
}
