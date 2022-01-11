<?php

namespace Vitorccs\LaravelCsv\Tests;

use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Facades\CsvImporter;
use Vitorccs\LaravelCsv\ServiceProviders\CsvServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CsvServiceProvider::class
        ];
    }

    /**
     * Get application timezone.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }

    /**
     * @param string $filename
     * @param CsvConfig|null $csvConfig
     * @return array
     */
    public function readFromDisk(string $filename, CsvConfig $csvConfig = null): array
    {
        $csvConfig = $csvConfig ?: CsvImporter::getConfig();

        CsvImporter::setConfig($csvConfig);

        $contents = CsvImporter::fromDisk($filename);

        Storage::disk($csvConfig->disk)->delete($filename);

        return $contents;
    }

    /**
     * @param $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->useStoragePath(realpath(__DIR__ . '/Data/Storage'));

        $app['config']->set('app.debug', env('APP_DEBUG', true));

        $app['config']->set('filesystems.default', 'local');
        $app['config']->set('filesystems.disks.local.root', realpath(__DIR__ . '/Data/Storage'));

        $app['config']->set('filesystems.disks.samples.driver', 'local');
        $app['config']->set('filesystems.disks.samples.root', realpath(__DIR__ . '/Data/Samples'));

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => env('DB_DRIVER', 'sqlite'),
            'host'     => env('DB_HOST'),
            'port'     => env('DB_PORT'),
            'database' => env('DB_DATABASE', ':memory:'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'prefix'   => env('DB_PREFIX')
        ]);


    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Data/Database/Migrations');

        // Provides support for the previous generation of Laravel factories (<= 7.x) for Laravel 8.x+.
        $this->withFactories(__DIR__ . '/Data/Database/Factories');
    }
}
