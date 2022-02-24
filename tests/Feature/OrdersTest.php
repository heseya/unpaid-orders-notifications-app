<?php

namespace Tests\Feature;

use App\Models\Api;
use App\Models\StoreUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    private Api $api;
    private StoreUser $user;

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

        $this->user = new StoreUser(1, 'User', '', ['show_orders']);
    }

    public function testApiUnregistered(): void
    {
        $this->get('/orders?api=https://missing.com&format=csv')
            ->assertStatus(422);
    }

    public function testApiUnauthorized(): void
    {
        $this->mockApiUnauthorized();

        $this->get("/orders?api={$this->api->url}&format=csv")
            ->assertForbidden();
    }

    public function testApiUnauthenticated(): void
    {
        $this->mockApiUnauthorizedWithPermission();
        $this->mockApiOrders();

        $response = $this->actingAs($this->user)->get("/orders?api={$this->api->url}&format=csv");

        $response
            ->assertStatus(200)
            ->assertDownload('orders.csv');

        $this->assertEquals(
            '"id","code","email","summary","shipping_price","summary_paid","paid","created_at","status","delivery_address.name","delivery_address.address","delivery_address.zip","delivery_address.city","delivery_address.country_name","delivery_address.phone","shipping_method"
"1","H5N1","email@email.com","1943.99 PLN","17.99 PLN","303 PLN","","2022-01-18T12:06:27.000000Z","Nowe","Address name","Address 17","89-464","City","Poland","123 456 789","dpd"
',
            $response->getFile()->getContent(),
        );
    }

    public function testApiMissing(): void
    {
        $this->actingAs($this->user)->get('/orders?api=https://missing.com&format=csv')
            ->assertStatus(404);
    }

    public function testApiOrdersInvalidFormat(): void
    {
        $this->actingAs($this->user)->get("/orders?api={$this->api->url}&format=png")
            ->assertStatus(422);
    }

    public function testApiHasNoOrders(): void
    {
        $this->mockApiNoOrders();

        $this->actingAs($this->user)->get("/orders?api={$this->api->url}&format=csv")
            ->assertStatus(200)
            ->assertSeeText('');
    }

    public function testApiOrders(): void
    {
        $this->mockApiOrders();

        $response = $this->actingAs($this->user)->get("/orders?api={$this->api->url}&format=csv");

        $response
            ->assertStatus(200)
            ->assertDownload('orders.csv');

        $this->assertEquals(
            '"id","code","email","summary","shipping_price","summary_paid","paid","created_at","status","delivery_address.name","delivery_address.address","delivery_address.zip","delivery_address.city","delivery_address.country_name","delivery_address.phone","shipping_method"
"1","H5N1","email@email.com","1943.99 PLN","17.99 PLN","303 PLN","","2022-01-18T12:06:27.000000Z","Nowe","Address name","Address 17","89-464","City","Poland","123 456 789","dpd"
',
            $response->getFile()->getContent(),
        );
    }

    public function testApiOrdersDefaultFormat(): void
    {
        $this->mockApiOrders();

        $response = $this->actingAs($this->user)->get("/orders?api={$this->api->url}");

        $response
            ->assertStatus(200)
            ->assertDownload('orders.csv');

        $this->assertEquals(
            '"id","code","email","summary","shipping_price","summary_paid","paid","created_at","status","delivery_address.name","delivery_address.address","delivery_address.zip","delivery_address.city","delivery_address.country_name","delivery_address.phone","shipping_method"
"1","H5N1","email@email.com","1943.99 PLN","17.99 PLN","303 PLN","","2022-01-18T12:06:27.000000Z","Nowe","Address name","Address 17","89-464","City","Poland","123 456 789","dpd"
',
            $response->getFile()->getContent(),
        );
    }

    private function mockApiNoOrders(): void
    {
        Http::fake([
            "{$this->api->url}/orders?limit=500&page=1" => Http::response([
                'data' => [],
                'meta' => [
                    'last_page' => 1,
                    'currency' => [
                        'symbol' => 'PLN',
                    ],
                ],
            ])
        ]);
    }

    private function mockApiOrders(): void
    {
        Http::fake([
            "{$this->api->url}/orders?limit=500&page=1" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'code' => 'H5N1',
                        'email' => 'email@email.com',
                        'currency' => 'PLN',
                        'summary' => 1943.99,
                        'summary_paid' => 303,
                        'shipping_price' => 17.99,
                        'paid' => false,
                        'comment' => 'Comment',
                        'created_at' => '2022-01-18T12:06:27.000000Z',
                        'status' => [
                            'name' => 'Nowe',
                        ],
                        'delivery_address' => [
                            'name' => 'Address name',
                            'address' => 'Address 17',
                            'vat' => null,
                            'zip' => '89-464',
                            'city' => 'City',
                            'country' => 'PL',
                            'country_name' => 'Poland',
                            'phone' => '123 456 789',
                        ],
                        'shipping_method' => [
                            'name' => 'dpd'
                        ],
                    ],
                ],
                'meta' => [
                    'last_page' => 1,
                    'currency' => [
                        'symbol' => 'PLN',
                    ],
                ],
            ])
        ]);
    }

    private function mockApiUnauthorized(): void
    {
        Http::fake([
            "{$this->api->url}/auth/check/" => Http::response([
                'data' => [
                    'id' => null,
                    'name' => 'Unauthenticated',
                    'avatar' => '',
                    'permissions' => [],
                ],
            ])
        ]);
    }

    private function mockApiUnauthorizedWithPermission(): void
    {
        Http::fake([
            "{$this->api->url}/auth/check/" => Http::response([
                'data' => [
                    'id' => null,
                    'name' => 'Unauthenticated',
                    'avatar' => '',
                    'permissions' => [
                        'show_orders',
                    ],
                ],
            ])
        ]);
    }
}
