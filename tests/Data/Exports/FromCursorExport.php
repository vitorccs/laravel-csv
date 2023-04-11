<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Illuminate\Support\LazyCollection;
use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromCollection;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;

class FromCursorExport implements FromCollection
{
    use Exportable;

    public function collection(): LazyCollection
    {
        return TestUser::cursor();
    }
}