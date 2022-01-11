<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\WithHeadings;

class WithHeadingsExport implements WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'A',
            'B',
            'C'
        ];
    }
}
