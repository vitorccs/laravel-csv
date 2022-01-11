<?php

namespace Vitorccs\LaravelCsv\Services;

use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Helpers\CsvHelper;

class Reader
{
    /**
     * @var CsvConfig
     */
    private CsvConfig $csvConfig;

    /**
     * @param CsvConfig $csvConfig
     */
    public function __construct(CsvConfig $csvConfig)
    {
        $this->csvConfig = $csvConfig;
    }

    /**
     * @param string $path
     * @return array
     */
    public function generate(string $path): array
    {
        return array_map(function (string $row) {
            $row = str_replace(CsvHelper::getBom(), '', $row);
            return str_getcsv(
                $row,
                $this->csvConfig->csv_delimiter,
                $this->csvConfig->csv_enclosure,
                $this->csvConfig->csv_escape
            );
        }, file($path));
    }
}
