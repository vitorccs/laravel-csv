<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Illuminate\Support\Collection;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromCollection;

class FromCollectionExport implements FromCollection
{
    use Exportable;

    public function collection(): Collection
    {
        return collect([
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
        ]);
    }
}
