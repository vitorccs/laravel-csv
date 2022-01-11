<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Vitorccs\LaravelCsv\Concerns\WithMapping;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;

class WithMappingExportSimple implements FromQuery, WithMapping
{
    use Exportable;

    public function query()
    {
        return TestUser::query();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
        ];
    }
}
