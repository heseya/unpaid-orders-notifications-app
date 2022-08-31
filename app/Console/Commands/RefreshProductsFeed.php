<?php

namespace App\Console\Commands;

use App\Dtos\ProductsExportDto;
use App\Exceptions\SettingNotFoundException;
use App\Models\Api;
use App\Services\Contracts\ProductsServiceContract;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class RefreshProductsFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh products feed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private ProductsServiceContract $productsService,
    ){
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $apis = Api::all()->each(function ($api) {
            $url = $api->url;
            $this->info("[$url] Processing");

            $dto = new ProductsExportDto(
                api: $api->url,
                format: Config::get('export.default_format'),
                params: Collection::make(),
            );

            try {
                $this->productsService->reloadProducts($api, $dto, true);
                $this->productsService->reloadProducts($api, $dto, false);
            } catch (Exception $exception) {
                if ($exception instanceof SettingNotFoundException) {
                    $this->info("[$url] Skipping");
                } else {
                    $this->error("[$url] " . $exception->getMessage());
                }
            }
        })->count();

        $this->info("Processed $apis stores");

        return 1;
    }
}
