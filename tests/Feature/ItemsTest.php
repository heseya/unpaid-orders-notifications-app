<?php

namespace Tests\Feature;

use App\Models\Api;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class ItemsTest extends TestCase
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

    public function testApiMissing(): void
    {
        $this->get('/items?api=https://missing.com&format=csv')
            ->assertStatus(404);
    }

    public function testApiItemsInvalidFormat(): void
    {
        $this->get("/items?api={$this->api->url}&format=png")
            ->assertStatus(422);
    }

    public function testApiHasNoItems(): void
    {
        $this->mockApiNoItems();

        $this->get("/items?api={$this->api->url}&format=csv")
            ->assertStatus(200)
            ->assertSeeText('');
    }

    public function testApiItems(): void
    {
        $this->mockApiItems();

        $response = $this->get("/items?api={$this->api->url}&format=csv");

        $response
            ->assertStatus(200)
            ->assertDownload('items.csv');

        $this->assertEquals(
            '"id","name","sku","quantity"
"1","Name","Sku","123"
',
            $response->getFile()->getContent(),
        );
    }

    public function testApiItemsDefaultFormat(): void
    {
        $this->mockApiItems();

        $response = $this->get("/items?api={$this->api->url}");

        $response
            ->assertStatus(200)
            ->assertDownload('items.csv');

        $this->assertEquals(
            '"id","name","sku","quantity"
"1","Name","Sku","123"
',
            $response->getFile()->getContent(),
        );
    }

    private function mockApiNoItems(): void
    {
        Http::fake([
            "{$this->api->url}/items?limit=500&page=1" => Http::response([
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

    private function mockApiItems(): void
    {
        Http::fake([
            "{$this->api->url}/items?limit=500&page=1" => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Name',
                        'sku' => 'Sku',
                        'quantity' => 123,
                    ]
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
}
