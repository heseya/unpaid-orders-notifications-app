<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;

use function Pest\Laravel\{getJson};

it('shows app info', function () {
    getJson('/')
        ->assertOk()
        ->assertJsonFragment([
            'name' => Config::get('app.name'),
            'author' => 'Heseya',
            'description' => Config::get('app.description'),
        ]);
});
