<?php

namespace Vitorccs\LaravelCsv\Facades;

use Illuminate\Support\Facades\Facade;
use Vitorccs\LaravelCsv\Entities\CsvConfig;

/**
 * @method static CsvConfig getConfig()
 * @method static array fromDisk(string $filename, ?string $disk = null)
 * @method static void setConfig(CsvConfig $config)
 */
class CsvImporter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'csv_importer';
    }
}
