<?php

namespace App\Services\Contracts;

use App\Models\Api;
use Illuminate\Http\Client\Response;

interface ApiServiceContract
{
    /**
     * @throws Exception
     */
    public function get(
        Api $api,
        string $url,
        array $headers,
        bool $tryRefreshing,
    ): Response;

    /**
     * @throws Exception
     */
    public function post(
        Api $api,
        string $url,
        array $data ,
        array $headers,
        bool $tryRefreshing,
    ): Response;

    /**
     * @throws Exception
     */
    public function patch(
        Api $api,
        string $url,
        array $data,
        array $headers,
        bool $tryRefreshing,
    ): Response;

    /**
     * @throws Exception
     */
    public function delete(
        Api $api,
        string $url,
        array $data,
        array $headers,
        bool $tryRefreshing,
    ): Response;
}
