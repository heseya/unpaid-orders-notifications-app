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
        $store_front_url = null;

        if ($with_values) {
            $api = Api::where('url', $api_url)->firstOrFail();
            $settings = $api->settings;

            $store_front_url = $settings?->first()->store_front_url;
        }

        $configs = Collection::make([]);
        $configs->push($this->getStoreFrontUrl($with_values, $store_front_url));

        $reports = Config::get('export.reports');
        $formats = Config::get('export.formats');

        foreach ($reports as $report) {
            foreach ($formats as $format) {
                $configs->push($this->generateField($report, $format, $api_url));
            }
        }

        return $configs;
    }

    private function getStoreFrontUrl(bool $with_values, string|null $url): array
    {
        $result = Config::get('settings.store_front_url');

        if ($with_values) {
            $result = Arr::add($result, 'value', $url);
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
