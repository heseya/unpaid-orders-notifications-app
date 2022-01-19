<?php

namespace Tests\Feature;

use App\Models\Api;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductsTest extends TestCase
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

    public function productHiddenProvider(): array
    {
        return [
            'as products-private' => ['products-private', ''],
            'as products' => ['products', '&public=1'],
        ];
    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiMissing($report, $param)
    {
        $this->get("/{$report}?api=https://missing.com&format=csv")
            ->assertStatus(404);
    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiNoProductUrl($report, $param)
    {
        $this->mockApiNoProducts($param);

        $this->get("/{$report}?api={$this->api->url}&format=csv")
            ->assertStatus(404);
    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiInvalidFormat($report, $param)
    {
        $this->get("/{$report}?api={$this->api->url}&format=png")
            ->assertStatus(422);
    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiHasNoProducts($report, $param)
    {
        $this->setApiProductsUrl();
        $this->mockApiNoProducts($param);

        $this->get("/{$report}?api={$this->api->url}&format=csv")
            ->assertStatus(200)
            ->assertSeeText(
                '',
            );
    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiProducts($report, $param)
    {
        $this->setApiProductsUrl();
        $this->mockApiProducts($param);

        $response = $this->get("/{$report}?api={$this->api->url}&format=csv");

        $response->assertStatus(200);
        $response->assertDownload("{$report}.csv");
        $this->assertEquals(
            '"id","title","description","availability","condition","price","link","image_link","brand"
"1","Name","Description","in stock","new","11.49 PLN","http://store.com/products/name","https://store.com/cover-1.png","Brak"
',
            $response->getFile()->getContent(),
        );
    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiProductsDefaultFormat($report, $param)
    {
        $this->setApiProductsUrl();
        $this->mockApiProducts($param);

        $response = $this->get("/{$report}?api={$this->api->url}");

        $response->assertStatus(200);
        $response->assertDownload("{$report}.csv");
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

    private function mockApiNoProducts($param) {
        Http::fake([
            "{$this->api->url}/products?limit=500&page=1&full{$param}" => Http::response([
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

    private function mockApiProducts($param) {
        Http::fake([
            "{$this->api->url}/products?limit=500&page=1&full{$param}" => Http::response([
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
