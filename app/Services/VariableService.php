<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Feed;
use App\Models\Field;
use App\Resolvers\AdditionalImageResolver;
use App\Resolvers\AttrsResolver;
use App\Resolvers\AvailabilityResolver;
use App\Resolvers\CategoryResolver;
use App\Resolvers\CoverResolver;
use App\Resolvers\EanResolver;
use App\Resolvers\GlobalResolver;
use App\Resolvers\ImgsResolver;
use App\Resolvers\LocalResolver;
use App\Resolvers\PriceResolver;
use App\Resolvers\ProductUrlResolver;
use App\Resolvers\ResponseResolver;
use App\Resolvers\SalePriceResolver;
use App\Resolvers\ShippingPriceResolver;
use App\Resolvers\StringResolver;
use App\Services\Contracts\VariableServiceContract;
use Illuminate\Support\Str;

class VariableService implements VariableServiceContract
{
    private const RESOLVERS = [
        // Global
        '@shipping_price' => ShippingPriceResolver::class,

        // Local
        '#cover' => CoverResolver::class,
        '#additional_image' => AdditionalImageResolver::class,
        '#availability' => AvailabilityResolver::class,
        '#price' => PriceResolver::class,
        '#sale_price' => SalePriceResolver::class,
        '#ean' => EanResolver::class,
        '#product_url' => ProductUrlResolver::class,
        '#category' => CategoryResolver::class,
        '#attrs' => AttrsResolver::class,
        '#imgs' => ImgsResolver::class,
    ];

    public function resolve(Feed $feed): array
    {
        $fields = [];

        foreach ($feed->fields as $key => $valueKey) {
            $fields[] = new Field(
                $feed,
                $key,
                $valueKey,
                $this->getResolver($valueKey),
            );
        }

        return $fields;
    }

    public function getResolver(string $key): GlobalResolver|LocalResolver
    {
        $key = Str::before($key, ' ');

        if (array_key_exists($key, self::RESOLVERS)) {
            $class = self::RESOLVERS[$key];

            return new $class();
        }

        if (Str::startsWith($key, '$')) {
            return new ResponseResolver();
        }

        return new StringResolver();
    }
}
