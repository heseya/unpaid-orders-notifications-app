<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ApiAuthenticationException;
use App\Exceptions\ApiAuthorizationException;
use App\Exceptions\ApiClientErrorException;
use App\Exceptions\ApiConnectionException;
use App\Exceptions\ApiServerErrorException;
use App\Models\Api;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

final readonly class ApiService
{
    /**
     * @throws ApiAuthenticationException
     * @throws ApiAuthorizationException
     * @throws ApiClientErrorException
     * @throws ApiConnectionException
     * @throws ApiServerErrorException
     */
    public function get(
        Api $api,
        string $url,
        array $parameters = [],
        array $headers = [],
    ): Response {
        return $this->send(
            $api,
            'get',
            $url,
            null,
            $headers,
            parameters: $parameters,
        );
    }

    /**
     * @throws ApiAuthenticationException
     * @throws ApiAuthorizationException
     * @throws ApiClientErrorException
     * @throws ApiConnectionException
     * @throws ApiServerErrorException
     */
    public function post(
        Api $api,
        string $url,
        array $data = [],
        array $headers = [],
        bool $tryRefreshing = true,
    ): Response {
        return $this->send($api, 'post', $url, $data, $headers, $tryRefreshing);
    }

    /**
     * @throws ApiAuthenticationException
     * @throws ApiAuthorizationException
     * @throws ApiClientErrorException
     * @throws ApiConnectionException
     * @throws ApiServerErrorException
     */
    public function patch(
        Api $api,
        string $url,
        array $data = [],
        array $headers = [],
        bool $tryRefreshing = true,
    ): Response {
        return $this->send($api, 'patch', $url, $data, $headers, $tryRefreshing);
    }

    /**
     * @throws ApiAuthenticationException
     * @throws ApiAuthorizationException
     * @throws ApiClientErrorException
     * @throws ApiConnectionException
     * @throws ApiServerErrorException
     */
    public function delete(
        Api $api,
        string $url,
        array $data = [],
        array $headers = [],
        bool $tryRefreshing = true,
    ): Response {
        return $this->send($api, 'delete', $url, $data, $headers, $tryRefreshing);
    }

    /**
     * @throws ApiAuthenticationException
     * @throws ApiAuthorizationException
     * @throws ApiClientErrorException
     * @throws ApiConnectionException
     * @throws ApiServerErrorException
     */
    private function refreshToken(Api $api): Api
    {
        try {
            $response = $this->send($api, 'post', '/auth/refresh', [
                'refresh_token' => $api->refresh_token,
            ], [], false, false);
        } catch (ApiAuthenticationException) {
            throw new ApiAuthenticationException('Failed refreshing integration token');
        }

        $api->update([
            'integration_token' => $response->json('data.token'),
            'refresh_token' => $response->json('data.refresh_token'),
        ]);

        return $api;
    }

    /**
     * @throws ApiAuthenticationException
     * @throws ApiAuthorizationException
     * @throws ApiClientErrorException
     * @throws ApiConnectionException
     * @throws ApiServerErrorException
     */
    private function send(
        Api $api,
        string $method,
        string $url,
        ?array $data = [],
        array $headers = [],
        bool $tryRefreshing = true,
        bool $withToken = true,
        array $parameters = [],
    ): Response {
        try {
            $request = Http::acceptJson()
                ->asJson()
                ->withHeaders($headers)
                ->withUrlParameters($parameters);

            if ($withToken) {
                $request = $request->withToken($api->integration_token);
            }

            $fullUrl = rtrim($api->url, '/') . '/' . trim($url, '/');

            $response = match ($method) {
                'post' => $request->post($fullUrl, $data),
                'patch' => $request->patch($fullUrl, $data),
                'delete' => $request->delete($fullUrl, $data),
                default => $request->get($fullUrl, $data),
            };
        } catch (Throwable) {
            throw new ApiConnectionException('Cannot reach the API');
        }

        if ($response->failed()) {
            if ($response->serverError()) {
                Log::error("API responded with an Error {$response->status()}", (array) $response->json());
                throw new ApiServerErrorException("API responded with an Error {$response->status()}");
            }

            if ($response->status() === 403) {
                throw new ApiAuthorizationException('This action is unauthorized by API');
            }

            if ($response->status() !== 401) {
                Log::error("API responded with an Error {$response->status()}", (array) $response->json());
                throw new ApiClientErrorException("API responded with an Error {$response->status()}");
            }

            if ($tryRefreshing === false) {
                throw new ApiAuthenticationException('Integration token was rejected by API');
            }

            $api = $this->refreshToken($api);
            $response = $this->send($api, $method, $url, $data, $headers, false);
        }

        return $response;
    }
}
