<?php

use Illuminate\Support\Facades\Config;

use function Pest\Laravel\{getJson};

it('shows app info', function () {
    getJson('/')
        ->assertOk()
        ->assertJsonFragment([
            'name' => Config::get('app.name'),
            'author' => 'Heseya',
            'description' => Config::get('app.description'),
            'microfrontend_url' => 'http://localhost/front',
            'icon' => 'http://localhost/logo.png',
        ]);
});
