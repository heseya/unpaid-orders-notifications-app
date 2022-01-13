<?php

namespace Tests\Feature;

use App\Models\Api;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductsCsvTest extends TestCase
{
    use RefreshDatabase;

    private Api $api;

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
    }

    public function testApiMissing()
    {
        $this->get('/products?api=https://missing.com')
            ->assertStatus(404);
    }

    public function testApiNoProductUrl()
    {
        $this->mockApiNoProducts();

        $this->get("/products?api={$this->api->url}")
            ->assertStatus(404);
    }

    public function testApiHasNoProducts()
    {
        $this->setApiProductsUrl();
        $this->mockApiNoProducts();

        $this->get("/products?api={$this->api->url}")
            ->assertStatus(200)
            ->assertSeeText(
                'id,title,description,availability,condition,price,link,image_link,brand',
            );
    }

    public function testApiProducts()
    {
        $this->setApiProductsUrl();
        $this->mockApiProducts();

        $response = $this->get("/products?api={$this->api->url}");

        $response->assertStatus(200);
        $this->assertEquals(
            "id,title,description,availability,condition,price,link,image_link,brand\n" .
            '1,Name,Description,"in stock",new,"11.49 PLN",http://store.com/products/name,' .
            "https://store.com/cover-1.png,Brak\n",
            $response->content(),
        );
    }

    private function setApiProductsUrl() {
        $this->api->settings()->create([
            'store_front_url' => "http://store.com/products/",
        ]);
    }

    private function mockApiNoProducts() {
        Http::fake([
            "{$this->api->url}/products?limit=500&full&page=1" => Http::response([
                'data' => [],
                'meta' => [
                    'last_page' => 1,
                    'currency' => [
                        'symbol' => 'PLN',
                    ],
                ],
            ]),
        ]);
    }

    private function mockApiProducts() {
        Http::fake([
            "{$this->api->url}/products?limit=500&full&page=1" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Name',
                        'description_short' => 'Description',
                        'available' => true,
                        'price' => 11.49,
                        'slug' => 'name',
                        'cover' => [
                            'url' => 'https://store.com/cover-1.png',
                        ],
                    ],
                ],
                'meta' => [
                    'last_page' => 1,
                    'currency' => [
                        'symbol' => 'PLN',
                    ],
                ],
            ]),
        ]);
    }
}
