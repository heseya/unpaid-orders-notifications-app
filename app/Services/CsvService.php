<?php

namespace App\Services;

use App\Models\Api;
use App\Services\Contracts\CsvServiceContract;
use Illuminate\Support\Collection;
use League\Csv\Exception as CsvException;
use League\Csv\Writer;

class CsvService implements CsvServiceContract
{

    /**
     * @throws CsvException
     */
    public function productsToCsv(Collection $products, Api $api): string
    {
        $settings = $api->settings()->firstOrFail();

        $products = $products->filter(
            fn ($product) => $product['cover'] !== null && $product['description_short'] !== null,
        );

        $products = $products->map(
            fn ($product) => [
                $product['id'],
                $product['name'],
                $product['description_short'],
                $product['available'] ? 'in stock' : 'out of stock',
                'new',
                "{$product['price']} {$product['currency']}",
                $settings->store_front_url . $product['slug'],
                $product['cover']['url'],
                'Brak',
            ],
        )->unique();

        $csv = Writer::createFromString(
            "id,title,description,availability,condition,price,link,image_link,brand\n",
        );

        $csv->insertAll($products);

        return $csv->toString();
    }
}
