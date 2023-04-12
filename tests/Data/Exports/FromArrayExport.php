<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromArray;

class FromArrayExport implements FromArray
{
    use Exportable;

    public function array(): array
    {
        return [
            ['a1', 'b1', 'c1'],
            ['a2', 'b2', 'c2'],
            ['a3', 'b3', 'c3'],
            ['a4', 'b4', 'c4'],
            ['a5', 'b5', 'c5'],
            ['a6', 'b6', 'c6'],
            ['a7', 'b7', 'c7'],
            ['a8', 'b8', 'c8'],
            ['a9', 'b9', 'c9'],
            ['a10', 'b10', 'c10']
        ];
    }
}
