<?php

namespace Tests\Feature;

use App\Models\Api;
use App\Models\StoreUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    private Api $api;
    private StoreUser $user;
    private string $expectedFileContent;

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

        $this->user = new StoreUser(1, 'User', '', ['show_products', 'show_products_private']);

        $this->expectedFileContent = 'id,gtin13,title,description,availability,condition,price,sale_price,link,image_link,additional_image_link,brand,google_product_category,shipping(country:price),product_type,custom_label_0
1,,"Name",Description,in stock,new,11.49 PLN,11.49 PLN,http://store.com/products/name,https://store.com/cover-1.png,https://store.com/cover-2.png,Exists,123,PL:6.99 PLN,"Set name",""
';
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
    public function testApiUnregistered($report, $param): void
    {
        $this->get("/{$report}?api=https://missing.com&format=csv")
            ->assertStatus(422);
    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiUnauthorized($report, $param): void
    {
        $this->mockApiUnauthorized();

        $this->get("/{$report}?api={$this->api->url}&format=csv")
            ->assertForbidden();
    }

//    /**
//     * @dataProvider productHiddenProvider
//     */
//    public function testApiUnauthenticated($report, $param): void
//    {
//        Storage::fake();
//
//        $this->mockApiUnauthorizedWithPermission();
//        $this->setApiProductsUrl();
//        $this->mockApiShipping();
//
//        $this->mockApiProducts('');
//        $this->mockApiProducts('&public=1');
//        Artisan::call('refresh:products');
//
//        $response = $this
//            ->actingAs($this->user)
//            ->json('GET', $report, ['api' => $this->api->url, 'format' => 'csv']);
//
////        dd($response->json());
//
//        $response->assertStatus(200);
//        $response->assertDownload($this->api->getKey() . "-{$report}.csv");
//        $this->assertEquals(
//            $this->expectedFileContent,
//            $response->streamedContent(),
//        );
//
//        Storage::assertExists($this->api->getKey() . "-$report.csv");
//
//        // check if another request not request any data form api
//        Http::fake();
//        $this->json('GET', $report, ['api' => $this->api->url, 'format' => 'csv']);
//        Http::assertNothingSent();
//    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiMissing($report, $param)
    {
        $this->actingAs($this->user)->get("/{$report}?api=https://missing.com&format=csv")
            ->assertStatus(404);
    }

//    /**
//     * @dataProvider productHiddenProvider
//     */
//    public function testApiNoProductUrl($report, $param)
//    {
//        $this->mockApiProducts('');
//        $this->mockApiProducts('&public=1');
//        Artisan::call('refresh:products');
//
//        $this->actingAs($this->user)->get("/{$report}?api={$this->api->url}&format=csv")
//            ->assertStatus(422)
//            ->assertJsonFragment(['message' => 'Storefront URL is not configured']);
//    }

    /**
     * @dataProvider productHiddenProvider
     */
    public function testApiInvalidFormat($report, $param)
    {
        $this->actingAs($this->user)->get("/{$report}?api={$this->api->url}&format=png")
            ->assertStatus(422);
    }

//    /**
//     * @dataProvider productHiddenProvider
//     */
//    public function testApiHasNoProducts($report, $param)
//    {
//        $this->setApiProductsUrl();
//        $this->mockApiNoProducts('');
//        $this->mockApiNoProducts('&public=1');
//
//        Artisan::call('refresh:products');
//
//        $this->actingAs($this->user)->get("/{$report}?api={$this->api->url}&format=csv")
//            ->assertStatus(200)
//            ->assertSeeText(
//                '',
//            );
//    }
//
//    /**
//     * @dataProvider productHiddenProvider
//     */
//    public function testApiProducts($report, $param)
//    {
//        $this->setApiProductsUrl();
//        $this->mockApiShipping();
//
//        $this->mockApiProducts('');
//        $this->mockApiProducts('&public=1');
//        Artisan::call('refresh:products');
//
//        $response = $this->actingAs($this->user)->get("/{$report}?api={$this->api->url}&format=csv");
//
//        $response->assertStatus(200);
//        $response->assertDownload($this->api->getKey() . "-{$report}.csv");
//        $this->assertEquals(
//            $this->expectedFileContent,
//            $response->streamedContent(),
//        );
//    }
//
//    /**
//     * @dataProvider productHiddenProvider
//     */
//    public function testApiProductsDefaultFormat($report, $param)
//    {
//        $this->setApiProductsUrl();
//        $this->mockApiShipping();
//
//        $this->mockApiProducts('');
//        $this->mockApiProducts('&public=1');
//        Artisan::call('refresh:products');
//
//        $response = $this->actingAs($this->user)->get("/{$report}?api={$this->api->url}");
//
//        $response->assertStatus(200);
//        $response->assertDownload($this->api->getKey() . "-{$report}.csv");
//        $this->assertEquals(
//            $this->expectedFileContent,
//            $response->streamedContent(),
//        );
//    }

    private function setApiProductsUrl() {
        $this->api->settings()->create([
            'store_front_url' => "http://store.com/products/",
        ]);
    }

    private function mockApiShipping() {
        Http::fake([
            "{$this->api->url}/shipping-methods" => Http::response([
                'data' => [
                    [
                        'price' => 9.99,
                    ],
                    [
                        'price' => 6.99,
                    ],
                ],
                'meta' => [],
            ]),
        ]);
    }

    private function mockApiNoProducts($param) {
        Http::fake([
            "{$this->api->url}/products?full&limit=250&page=1{$param}" => Http::response([
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
            "{$this->api->url}/products?full&limit=250&page=1{$param}" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Name',
                        'description_html' => 'Description',
                        'available' => true,
                        'price_min' => 11.49,
                        'slug' => 'name',
                        'cover' => [
                            'url' => 'https://store.com/cover-1.png',
                        ],
                        'sets' => [
                            [
                                'name' => 'Set name',
                                'parent_id' => null,
                                'metadata' => [],
                            ],
                        ],
                        'gallery' => [
                            ['url' => 'https://store.com/cover-1.png'],
                            ['url' => 'https://store.com/cover-2.png'],
                        ],
                        'google_product_category' => 123,
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

    private function mockApiUnauthorized(): void
    {
        Http::fake([
            "{$this->api->url}/auth/check" => Http::response([
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
            "{$this->api->url}/auth/check" => Http::response([
                'data' => [
                    'id' => null,
                    'name' => 'Unauthenticated',
                    'avatar' => '',
                    'permissions' => [
                        'show_products',
                        'show_products_private',
                    ],
                ],
            ])
        ]);
    }
}
