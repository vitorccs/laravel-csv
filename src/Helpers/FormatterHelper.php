<?php

namespace Vitorccs\LaravelCsv\Helpers;

class FormatterHelper
{
    /**
     * @param \DateTime|string $date
     * @param string $format
     * @return string
     */
    public static function date($date, string $format): string
    {
        if (!($date instanceof \DateTime)) {
            return (string)$date;
        }

        return $date->format($format);
    }

    /**
     * @param float|int|string $number
     * @param int $decimals
     * @param string $decimalSep
     * @param string $thousandsSep
     * @return string
     */
    public static function number($number,
                                  int $decimals = 0,
                                  string $decimalSep = '.',
                                  string $thousandsSep = ','): string
    {
        if (!is_numeric($number)) {
            return (string)$number;
        }

        return number_format(
            $number,
            $decimals,
            $decimalSep,
            $thousandsSep,
        );
    }
}
