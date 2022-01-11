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
            ['a3', 'b3', 'c3']
        ]);
    }
}
