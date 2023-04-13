<?php

namespace App\Services;

use App\Enums\FieldType;
use App\Models\Feed;
use App\Models\Field;
use App\Resolvers\AdditionalImageResolver;
use App\Resolvers\AvailabilityResolver;
use App\Resolvers\CoverResolver;
use App\Resolvers\EanResolver;
use App\Resolvers\GlobalResolver;
use App\Resolvers\LocalResolver;
use App\Resolvers\PriceResolver;
use App\Resolvers\SalePriceResolver;
use App\Resolvers\ShippingPriceResolver;
use App\Services\Contracts\VariableServiceContract;
use Illuminate\Support\Str;

class VariableService implements VariableServiceContract
{
    private const RESOLVERS = [
        // Global
        '$shipping_price' => ShippingPriceResolver::class,

        // Local
        '#cover' => CoverResolver::class,
        '#additional_image' => AdditionalImageResolver::class,
        '#availability' => AvailabilityResolver::class,
        '#price' => PriceResolver::class,
        '#sale_price' => SalePriceResolver::class,
        '#ean' => EanResolver::class,
//        '#product_url' => ProductUrlResolver::class,
    ];

    public function resolve(Feed $feed): array
    {
        $fields = [];

        foreach ($feed->fields as $key => $valueKey) {
            $fields[] = new Field(
                $feed,
                $key,
                $valueKey,
                $this->resolveType($valueKey),
                $this->getResolver($valueKey),
            );
        }

        return $fields;
    }

    public function resolveType(string $key): FieldType
    {
        if (Str::of($key)->startsWith('$')) {
            return FieldType::VAR_GLOBAL;
        }

        if (Str::of($key)->startsWith('#')) {
            return FieldType::VAR_LOCAL;
        }

        return FieldType::STANDARD;
    }

    public function getResolver(string $key): GlobalResolver|LocalResolver|null
    {
        if (array_key_exists($key, self::RESOLVERS)) {
            $class = self::RESOLVERS[$key];

            return new $class();
        }

        return null;
    }
}
