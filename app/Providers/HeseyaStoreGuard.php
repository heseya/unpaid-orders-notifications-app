<?php

namespace App\Providers;

use App\Exceptions\ApiClientErrorException;
use App\Exceptions\UnknownApiException;
use App\Services\ApiService;
use App\Models\Api;
use App\Models\StoreUser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Throwable;

class HeseyaStoreGuard implements Guard
{
    private ?Authenticatable $user = null;
    private ?string $token = null;

    public function __construct()
    {
        Gate::before(function ($user, $ability) {
            if ($user instanceof StoreUser && $user->getPermissions()->contains($ability)) {
                return true;
            }
        });
    }

    public function check(): bool
    {
        return $this->user !== null;
    }

    public function guest(): bool
    {
        return false;
    }

    public function user(): ?Authenticatable
    {
        return $this->user ?? $this->fetchUser();
    }

    public function id()
    {
        return $this->user->getAuthIdentifier();
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function fetchUser(): ?Authenticatable
    {
        if ($this->getToken() === null || $this->getToken() === $this->token) {
            return null;
        }

        $payload = $this->getTokenPayload();
        $url = $payload['iss'];

        try {
            $api = Api::where('url', $url)->firstOrFail();
        } catch (Throwable) {
            throw new UnknownApiException('Unregistered API call');
        }

        $apiService = new ApiService();
        try {
            $response = $apiService->get($api, '/auth/profile/' . $this->getToken());
        } catch (ApiClientErrorException) {
            throw new AuthenticationException('Invalid identity_token');
        }

        $this->user = new StoreUser(
            $response->json('data.id'),
            $response->json('data.name'),
            $response->json('data.avatar'),
            $response->json('data.permissions'),
        );

        return $this->user;
    }

    public function getTokenPayload(): ?array
    {
        $token = $this->getToken();

        if ($token === null) {
            return null;
        }

        $payloadEncoded = Str::between($token, ".", ".");

        return json_decode(base64_decode($payloadEncoded), true);
    }

    private function getToken(): ?string
    {
        /** @var Request $request */
        $request = App::make(Request::class);

        return $request->bearerToken();
    }
}
