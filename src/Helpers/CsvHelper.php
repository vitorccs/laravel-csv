<?php

namespace Vitorccs\LaravelCsv\Helpers;

use Illuminate\Support\Str;

class CsvHelper
{
    /**
     * @var string
     */
    public static string $contentType = 'text/csv; charset=UTF-8';

    /**
     * @var string
     */
    public static string $extension = 'csv';

    /**
     * @return string
     */
    public static function filename(): string
    {
        return Str::uuid() . '.' . self::$extension;
    }

    /**
     * @return string
     */
    public static function getBom(): string
    {
        return "\xEF\xBB\xBF";
    }

    /**
     * @param int $number
     * @return string|null
     */
    public static function getColumnLetter(int $number): ?string
    {
        if ($number <= 0) {
            return null;
        }
        $alphabet = '';
        while ($number != 0) {
            $p = ($number - 1) % 26;
            $number = intval(($number - $p) / 26);
            $alphabet = chr(65 + $p) . $alphabet;
        }
        return $alphabet;
    }
}
