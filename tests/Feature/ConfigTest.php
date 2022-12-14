<?php

namespace Tests\Feature;

use App\Models\Api;
use App\Models\StoreUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    use RefreshDatabase;

    private Api $api;
    private StoreUser $user;
    private array $excepted;

    protected function setUp(): void
    {
        parent::setUp();

        $this->api = Api::create([
            'url' => 'https://exists.com',
            'name' => 'Exists',
            'version' => '2.0.0',
            'integration_token' => Str::random(),
            'refresh_token' => Str::random(),
            'uninstall_token' => Str::random(),
        ]);

        $this->user = new StoreUser(1, 'User', '', ['configure']);

        $this->excepted = [
            'key',
            'label',
            'placeholder',
            'type',
            'default_value',
            'required',
            'options',
        ];
    }

    public function testConfig(): void
    {
        $this->json('GET', '/config')
            ->assertOk()
            ->assertJsonStructure([
                $this->excepted,
            ]);
    }

    public function testConfigUserNoPermissions(): void
    {
        $user = new StoreUser(1, 'User', '', []);
        $this->actingAs($user)
            ->json('GET', '/config', [], ['X-Core-Url' => 'https://exists.com'])
            ->assertOk()
            ->assertJsonStructure([
                $this->excepted,
            ]);
    }

    public function testConfigUnauthenticated(): void
    {
        $this->mockApiUser();

        $this->json('GET', '/config', [], ['X-Core-Url' => 'https://exists.com'])
            ->assertOk()
            ->assertJsonStructure([
                $this->excepted + ['value'],
            ]);
    }

    public function testConfigUser(): void
    {
        $this->actingAs($this->user)
            ->json('GET', '/config', [], ['X-Core-Url' => 'https://exists.com'])
            ->assertOk()
            ->assertJsonStructure([
                $this->excepted + ['value'],
            ]);
    }

    public function testUpdateConfigUnauthorized(): void
    {
        $this->json('POST', '/config', [
            'store_front_url' => 'https://store.com',
        ])->assertForbidden();
    }

    public function testUpdateConfig(): void
    {
        $this->actingAs($this->user)->json('POST', '/config', [
            'store_front_url' => 'https://store.com',
        ], ['X-Core-Url' => 'https://exists.com'])
            ->assertOk()
            ->assertJsonStructure([
                $this->excepted + ['value'],
            ])
            ->assertJsonFragment([
                'key' => 'store_front_url',
                'value' => 'https://store.com/',
            ]);
    }

    private function mockApiUser(): void
    {
        Http::fake([
            "{$this->api->url}/auth/check" => Http::response([
                'data' => [
                    'id' => null,
                    'name' => 'Unauthenticated',
                    'avatar' => '',
                    'permissions' => [
                        'configure',
                    ],
                ],
            ]),
        ]);
    }
}
