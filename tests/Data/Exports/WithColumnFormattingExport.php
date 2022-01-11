<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Carbon\Carbon;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\WithColumnFormatting;
use Vitorccs\LaravelCsv\Enum\CellFormat;

class WithColumnFormattingExport implements FromArray, WithColumnFormatting
{
    use Exportable;

    public function array(): array
    {
        $format = 'Y-m-d H:i:s';
        $timestamp1 = '2021-02-03 4:05:06';
        $timestamp2 = '2021-12-31 23:15:46';

        return [
            [1, 2.3, Carbon::parse($timestamp1, 'UTC'), \DateTime::createFromFormat($format, $timestamp1)],
            [2, 5.300, Carbon::parse($timestamp2, 'UTC'), \DateTime::createFromFormat($format, $timestamp2)],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => CellFormat::INTEGER,
            'B' => CellFormat::DECIMAL,
            'C' => CellFormat::DATE,
            'D' => CellFormat::DATETIME
        ];
    }
}
