<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ApiAuthenticationException;
use App\Exceptions\ApiAuthorizationException;
use App\Exceptions\ApiClientErrorException;
use App\Exceptions\ApiConnectionException;
use App\Exceptions\ApiServerErrorException;
use App\Mail\UnpaidOrder;
use App\Models\Api;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

final readonly class SendService
{
    public function __construct(
        private ApiService $apiService,
    ) {}

    /**
     * @throws ApiServerErrorException
     * @throws ApiConnectionException
     * @throws ApiClientErrorException
     * @throws ApiAuthenticationException
     * @throws ApiAuthorizationException
     */
    public function sendForApi(Api $api): void
    {
        $from = Carbon::now()->subDays($api->orders_from_days)->startOfDay()->toAtomString();
        $to = Carbon::now()->subDays($api->orders_from_days)->endOfDay()->toAtomString();

        $lastPage = 1; // Get at least once

        for ($page = 1; $page <= $lastPage; ++$page) {
            $response = $this->apiService->get($api, '/orders', [
                'paid' => false,
                'from' => $from,
                'to' => $to,
                'limit' => 100,
                'page' => $page,
            ]);

            $lastPage = $response->json('meta.last_page');

            foreach ($response->json('data') as $order) {
                if ($order['payable']) {
                    Mail::to($order['email'])->send(
                        new UnpaidOrder($api, $order),
                    );
                }
            }
        }
    }
}
