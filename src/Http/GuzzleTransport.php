<?php

declare(strict_types=1);

/**
 * This file is part of Kwaishop PHP SDK.
 *
 * @link     https://github.com/westng/kwaishop-php-sdk
 * @document https://github.com/westng/kwaishop-php-sdk
 * @contact  westng
 * @license  https://github.com/westng/kwaishop-php-sdk/blob/main/LICENSE
 */

namespace Kwaishop\PhpSdk\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Kwaishop\PhpSdk\Config\Config;
use Kwaishop\PhpSdk\Exception\TransportException;

final class GuzzleTransport implements TransportInterface
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly Config $config,
    ) {
    }

    public function sendForm(string $url, array $formParams): array
    {
        try {
            $response = $this->client->request('POST', $url, [
                'connect_timeout' => $this->config->connectTimeout(),
                'timeout' => $this->config->readTimeout(),
                'http_errors' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'User-Agent' => $this->config->userAgent(),
                ],
                'form_params' => $formParams,
            ]);
        } catch (GuzzleException $exception) {
            throw new TransportException('HTTP transport failed: ' . $exception->getMessage(), previous: $exception);
        }

        return [
            'status' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => (string) $response->getBody(),
        ];
    }
}
