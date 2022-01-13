<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class InfoTest extends TestCase
{
    public function testInfoIndex(): void
    {
        $this->json('GET', '/')
            ->assertOk()
            ->assertJsonFragment([
                'name' => Config::get('app.name'),
                'author' => Config::get('app.author'),
                'description' => Config::get('app.description'),
                'microfrontend_url' => Config::get('app.microfrontend'),
                'icon' => Config::get('app.icon'),
            ]);
    }
}
