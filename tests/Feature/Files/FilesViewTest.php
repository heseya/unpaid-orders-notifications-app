<?php

declare(strict_types=1);

use App\Enums\AuthType;
use App\Models\Feed;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;

const UUID = '27861b54-0b64-4d35-8263-fd7af26c8254';

it('can\'t download file when file doesn\'t exist', function () {
    get('/file/{UUID}' . UUID . '.csv')->assertNotFound();
});

it('can\'t download file when doesn\'t own file', function () {
    Storage::fake();
    Storage::write('feeds/' . UUID, 'test');

    get('/file/' . UUID . '.csv')->assertNotFound();
});

it('can\'t download file when wile is protected', function () {
    Storage::fake();
    $api = mockApi();
    $feed = Feed::factory()->create([
        'api_id' => $api->getKey(),
        'auth' => AuthType::BASIC,
        'username' => 'test',
        'password' => 'test',
    ]);
    Storage::write($feed->path(), 'test');

    get("/file/{$feed->getKey()}")->assertUnauthorized();
});

it('downloads file', function () {
    Storage::fake();
    $api = mockApi();
    $feed = Feed::factory()->create(['api_id' => $api->getKey()]);
    Storage::write($feed->path(), 'test');

    get("/file/{$feed->getKey()}")->assertOk();
});

it('downloads protected file', function () {
    Storage::fake();
    $api = mockApi();
    $feed = Feed::factory()->create([
        'api_id' => $api->getKey(),
        'auth' => AuthType::BASIC,
        'username' => 'test',
        'password' => 'test',
    ]);
    Storage::write($feed->path(), 'test');

    get("/file/{$feed->getKey()}", [
        'Authorization' => 'Basic ' . base64_encode('test:test'),
    ])->assertOk();
});
