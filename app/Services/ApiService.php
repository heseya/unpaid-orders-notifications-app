<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Services\Contracts\ApiServiceContract;
use App\Models\Api;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class ApiService implements ApiServiceContract
{
    /**
     * @throws Exception
     */
    private function refreshToken(Api $api): Api
    {
        try {
            $response = $this->send($api, "post", "/auth/refresh", [
                "refresh_token" => $api->refresh_token,
            ], [], false, false);
        } catch (ApiException) {
            throw new ApiException("Failed refreshing integration token");
        }

        $api->update([
           "integration_token" => $response->json("data.token"),
           "refresh_token" => $response->json("data.refresh_token"),
        ]);

        return $api;
    }

    /**
     * @throws Exception
     */
    private function send(
        Api $api,
        string $method,
        string $url,
        ?array $data = [],
        array $headers = [],
        bool $tryRefreshing = true,
        bool $withToken = true,
    ): Response {
        try {
            $request = Http::acceptJson()->asJson()->withHeaders($headers);

            if ($withToken) {
                $request = $request->withToken($api->integration_token);
            }

            $response = match ($method) {
                "post" => $request->post($api->url . $url, $data),
                "patch" => $request->patch($api->url . $url, $data),
                "delete" => $request->delete($api->url . $url, $data),
                default => $request->get($api->url . $url, $data),
            };
        } catch (Throwable) {
            throw new ApiException("Cannot reach the API");
        }

        if ($response->failed()) {
            if ($response->status() === 403) {
                throw new ApiException("This action is unauthorized by API");
            }

            if ($response->status() !== 401) {
                throw new ApiException("API responded with an Error");
            }

            if ($tryRefreshing === false) {
                throw new ApiException("Integration token was rejected by API");
            }

            $api = $this->refreshToken($api);
            $response = $this->send($api, $method, $url, $data, $headers, false);
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    public function get(
        Api $api,
        string $url,
        array $headers = [],
        bool $tryRefreshing = true,
    ): Response {
        return $this->send($api, 'get', $url, null, $headers, $tryRefreshing);
    }

    /**
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
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
}
