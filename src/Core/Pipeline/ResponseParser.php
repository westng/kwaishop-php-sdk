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

namespace KwaiShopSDK\Core\Pipeline;

use KwaiShopSDK\Exception\AuthenticationException;
use KwaiShopSDK\Exception\AuthorizationException;
use KwaiShopSDK\Exception\BusinessException;
use KwaiShopSDK\Exception\RateLimitException;
use KwaiShopSDK\Exception\SignatureException;
use KwaiShopSDK\Exception\TransportException;
use KwaiShopSDK\Exception\ValidationException;
use KwaiShopSDK\Support\Arr;
use KwaiShopSDK\Support\Json;

final class ResponseParser
{
    /**
     * @return array<string, mixed>
     */
    public function parse(int $httpStatus, string $body): array
    {
        $payload = $this->decodePayload($httpStatus, $body);
        $primaryCode = $this->primaryCode($payload);
        $secondaryCode = $this->secondaryCode($payload);

        if ($httpStatus < 200 || $httpStatus >= 300) {
            if ($primaryCode !== null && !$this->isSuccess($primaryCode)) {
                $message = (string) Arr::first($payload, ['error_msg', 'msg', 'message', 'error'], 'Open platform request failed.');

                throw $this->mapException($message, $primaryCode, $secondaryCode, $payload, $body);
            }

            throw new TransportException(
                sprintf('Unexpected HTTP status %d.', $httpStatus),
                $httpStatus,
                rawResponseBody: $body
            );
        }

        if ($primaryCode === null) {
            throw new ValidationException('Missing response status field in gateway response.');
        }

        if ($this->isSuccess($primaryCode)) {
            return $payload;
        }

        $message = (string) Arr::first($payload, ['error_msg', 'msg', 'message', 'error'], 'Open platform request failed.');

        throw $this->mapException($message, $primaryCode, $secondaryCode, $payload, $body);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function primaryCode(array $payload): ?int
    {
        $value = Arr::first($payload, ['result', 'code', 'status', 'error_code']);

        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function secondaryCode(array $payload): ?int
    {
        $value = Arr::first($payload, ['sub_code', 'subCode', 'sub_status']);

        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function isSuccess(?int $primaryCode): bool
    {
        return $primaryCode === null || in_array($primaryCode, [1, 200], true);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function mapException(string $message, ?int $primaryCode, ?int $secondaryCode, array $payload, string $rawResponseBody): BusinessException
    {
        return match ($primaryCode) {
            21, 24 => new AuthenticationException($message, $primaryCode, $secondaryCode, $payload, $rawResponseBody),
            22, 25, 26 => new AuthorizationException($message, $primaryCode, $secondaryCode, $payload, $rawResponseBody),
            27, 28 => new SignatureException($message, $primaryCode, $secondaryCode, $payload, $rawResponseBody),
            15, 16, 17, 1016, 1017, 802000 => new RateLimitException($message, $primaryCode, $secondaryCode, $payload, $rawResponseBody),
            default => new BusinessException($message, $primaryCode, $secondaryCode, $payload, $rawResponseBody),
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function decodePayload(int $httpStatus, string $body): array
    {
        try {
            return Json::decode($body);
        } catch (ValidationException $exception) {
            if ($httpStatus < 200 || $httpStatus >= 300) {
                throw new TransportException(
                    sprintf('Unexpected HTTP status %d.', $httpStatus),
                    $httpStatus,
                    $exception,
                    $body
                );
            }

            throw $exception;
        }
    }
}
