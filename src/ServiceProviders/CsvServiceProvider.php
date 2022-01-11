<?php

namespace Vitorccs\LaravelCsv\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Vitorccs\LaravelCsv\CsvExporter;
use Vitorccs\LaravelCsv\CsvImporter;
use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Services\ExportableService;
use Vitorccs\LaravelCsv\Services\ImportableService;

class CsvServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->getConfigFile() => config_path('csv.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->getConfigFile(),
            'csv'
        );

        $this->app->singleton(CsvConfig::class, fn() => new CsvConfig());

        $this->app->bind('csv_exporter', function ($app) {
            return new CsvExporter(
                $app->make(ExportableService::class),
            );
        });

        $this->app->bind('csv_importer', function ($app) {
            return new CsvImporter(
                $app->make(ImportableService::class),
            );
        });
    }

    /**
     * @return string
     */
    private function getConfigFile(): string
    {
        return __DIR__ . '/../../config/csv.php';
    }
}
