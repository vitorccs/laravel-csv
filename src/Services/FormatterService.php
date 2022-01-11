<?php

namespace Vitorccs\LaravelCsv\Services;

use Carbon\Carbon;
use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Helpers\FormatterHelper;

class FormatterService
{
    /**
     * @var CsvConfig
     */
    public CsvConfig $config;

    /**
     * @param CsvConfig $config
     */
    public function __construct(CsvConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param Carbon|\DateTime|string $date
     * @return string
     */
    public function date($date): string
    {
        return FormatterHelper::date($date, $this->config->format_date);
    }

    /**
     * @param Carbon|\DateTime|string $date
     * @return string
     */
    public function datetime($date): string
    {
        return FormatterHelper::date($date, $this->config->format_datetime);
    }

    /**
     * @param int|float|string $number
     * @return string
     */
    public function decimal($number): string
    {
        return FormatterHelper::number(
            $number,
            $this->config->format_number_decimals,
            $this->config->format_number_decimal_sep,
            $this->config->format_number_thousand_sep
        );
    }

    /**
     * @param int|float|string $number
     * @return string
     */
    public static function integer($number): string
    {
        return FormatterHelper::number($number);
    }
}
