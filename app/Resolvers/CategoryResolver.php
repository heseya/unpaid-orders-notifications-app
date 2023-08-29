<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Field;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class CategoryResolver implements LocalResolver
{
    public static function resolve(Field $field, array $response): string
    {
        $sets = Collection::make(Arr::get($response, 'sets'));
        $computedSets = Collection::make();

        $find = $sets->firstWhere('children_ids', '===', []);
        $computedSets->push($find);

        while ($find !== null) {
            $find = $sets->firstWhere('id', '=', $find['parent_id']);
            if ($find) {
                $computedSets->push($find);
            }
        }

        return $computedSets->reverse()->implode('name', '\\');
    }
}
