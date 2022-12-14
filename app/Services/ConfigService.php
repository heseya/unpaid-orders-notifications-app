<?php

namespace App\Services;

use App\Models\Api;
use App\Services\Contracts\ConfigServiceContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class ConfigService implements ConfigServiceContract
{
    public function getConfigs(bool $with_values, string|null $api_url): Collection
    {
        if ($with_values) {
            /** @var Api $api */
            $api = Api::query()->where('url', $api_url)->firstOrFail();
            $settings = $api->settings()->first();

            $store_front_url = $settings?->store_front_url;
            $product_type_set_parent_filter = $settings?->product_type_set_parent_filter;
            $product_type_set_no_parent_filter = $settings?->product_type_set_no_parent_filter;
            $google_custom_label_metatag = $settings?->google_custom_label_metatag;
        }

        $configs = Collection::make([]);
        $configs->push($this->getConfigField(
            'store_front_url',
            $with_values,
            $store_front_url ?? null,
        ));
        $configs->push($this->getConfigField(
            'product_type_set_parent_filter',
            $with_values,
            $product_type_set_parent_filter ?? null,
        ));
        $configs->push($this->getConfigField(
            'product_type_set_no_parent_filter',
            $with_values,
            $product_type_set_no_parent_filter ?? false,
        ));
        $configs->push($this->getConfigField(
            'google_custom_label_metatag',
            $with_values,
            $google_custom_label_metatag ?? null,
        ));

        $reports = Config::get('export.reports');
        $formats = Config::get('export.formats');

        foreach ($reports as $report) {
            foreach ($formats as $format) {
                $configs->push($this->generateField($report, $format, $api_url));

                if ($with_values) {
                    $key = "{$format}_updated_at";
                    $configs->push($this->getUpdateField(
                        "{$format}_updated_at",
                        "Last update of {$format}",
                        $api->$key,
                    ));
                }
            }
        }

        return $configs;
    }

    private function getUpdateField(string $key, string $label, mixed $value): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'placeholder' => $value,
            'type' => 'datetime-local',
            'default_value' => null,
            'required' => false,
            'value' => $value,
        ];
    }

    private function getConfigField(string $setting, bool $with_values, $value): array
    {
        $result = Config::get("settings.{$setting}");

        if ($with_values) {
            $result = Arr::add($result, 'value', $value);
        }

        return $result;
    }

    private function generateField(string $report, string $format, string|null $api_url): array
    {
        $result = [
            'key' => $report . '_' . $format .'_url',
            'label' => Str::upper($format) . ' formatted ' . $report,
            'placeholder' => Config::get('app.url') . '/' . $report .'?api=api_url',
            'type' => 'text',
            'default_value' => null,
            'required' => false,
            'options' => [],
        ];

        if ($api_url) {
            $result = Arr::add(
                $result,
                'value',
                Config::get('app.url') . '/' . $report . '?api=' . $api_url . '&format=' . $format
            );
        }

        return $result;
    }
}
