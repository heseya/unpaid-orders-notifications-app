<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FileFormat;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle,
            'format' => FileFormat::XML,
            'query' => $this->faker->url,
            'fields' => ['test' => 'test'],
        ];
    }
}
