<?php

declare(strict_types=1);

use App\Models\Api;
use App\Models\Feed;
use App\Models\Field;
use App\Models\StoreUser;
use App\Resolvers\GlobalResolver;
use App\Resolvers\LocalResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function mockApi(): Api
{
    return Api::create([
        'url' => fake()->url(),
        'version' => '3.0.0',
        'integration_token' => Str::random(),
        'refresh_token' => Str::random(),
        'uninstall_token' => Str::random(),
    ]);
}

function mockField(LocalResolver|GlobalResolver $resolver): Field
{
    $api = mockApi();
    $feed = Feed::factory()->create(['api_id' => $api->getKey()]);

    return new Field($feed, 'test', 'test', $resolver);
}

function mockUser(Api $api): StoreUser
{
    Http::fake([
        "{$api->url}/auth/check" => Http::response([
            'data' => [
                'id' => $id = Illuminate\Support\Str::uuid(),
                'name' => 'Unauthenticated',
                'avatar' => '',
                'permissions' => [
                    'configure',
                ],
            ],
        ]),
    ]);

    return new StoreUser(
        $id,
        'Test User',
        '',
        [
            'configure',
        ],
        $api,
    );
}
