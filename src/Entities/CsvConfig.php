<?php

namespace Vitorccs\LaravelCsv\Entities;

class CsvConfig
{
    public int $chunk_size;

    public string $disk;

    public string $csv_delimiter;
    public string $csv_enclosure;
    public string $csv_escape;
    public bool $csv_bom;

    public string $format_date;
    public string $format_datetime;
    public int $format_number_decimals;
    public string $format_number_decimal_sep;
    public string $format_number_thousand_sep;

    public string $job_timeout;
    public string $job_attempts;
    public string $job_delay;

    public function __construct()
    {
        $config = config('csv');

        foreach ($config as $key => $value) {
            $this->{$key} = config("csv.{$key}");
        }
    }
}
