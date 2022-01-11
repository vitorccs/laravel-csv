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
            ['A 1', 'B 1', 'C 1'],
            ['A 2', 'B 2', 'C 2'],
            ['A 3', 'B 3', 'C 3']
        ];
    }
}
