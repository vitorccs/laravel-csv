<?php

namespace Vitorccs\LaravelCsv\Facades;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vitorccs\LaravelCsv\Entities\CsvConfig;

/**
 * @method static CsvConfig getConfig()
 * @method static int count(object $exportable)
 * @method static array toArray(object $exportable)
 * @method static string store(object $exportable, string $filename = null, ?string $disk = null, array $diskOptions = [])
 * @method static StreamedResponse download(object $exportable, string $filename)
 * @method static PendingDispatch queue(object $exportable, string $filename, ?string $disk = null, array $diskOptions = [])
 * @method static void setConfig(CsvConfig $config)
 */
class CsvExporter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'csv_exporter';
    }
}
