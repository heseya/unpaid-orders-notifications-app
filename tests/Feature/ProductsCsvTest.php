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
        $this->get('/products?api=https://missing.com&format=csv')
            ->assertStatus(404);
    }

    public function testApiNoProductUrl()
    {
        $this->mockApiNoProducts();

        $this->get("/products?api={$this->api->url}&format=csv")
            ->assertStatus(404);
    }

    public function testApiNoProductInvalidFormat()
    {
        $this->get("/products?api={$this->api->url}&format=png")
            ->assertStatus(422);
    }

    public function testApiHasNoProducts()
    {
        $this->setApiProductsUrl();
        $this->mockApiNoProducts();

        $this->get("/products?api={$this->api->url}&format=csv")
            ->assertStatus(200)
            ->assertSeeText(
                '',
            );
    }

    public function testApiProducts()
    {
        $this->setApiProductsUrl();
        $this->mockApiProducts();

        $response = $this->get("/products?api={$this->api->url}&format=csv");

        $response->assertStatus(200);
        $response->assertDownload('products.csv');
        $this->assertEquals(
            '"id","title","description","availability","condition","price","link","image_link","brand"
"1","Name","Description","in stock","new","11.49 PLN","http://store.com/products/name","https://store.com/cover-1.png","Brak"
',
            $response->getFile()->getContent(),
        );
    }

    public function testApiProductsDefaultFormat()
    {
        $this->setApiProductsUrl();
        $this->mockApiProducts();

        $response = $this->get("/products?api={$this->api->url}");

        $response->assertStatus(200);
        $response->assertDownload('products.csv');
        $this->assertEquals(
            '"id","title","description","availability","condition","price","link","image_link","brand"
"1","Name","Description","in stock","new","11.49 PLN","http://store.com/products/name","https://store.com/cover-1.png","Brak"
',
            $response->getFile()->getContent(),
        );
    }

    private function setApiProductsUrl() {
        $this->api->settings()->create([
            'store_front_url' => "http://store.com/",
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
