<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Vitorccs\LaravelCsv\Concerns\WithMapping;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;

class WithMappingExport implements FromQuery, WithMapping
{
    use Exportable;

    public function query()
    {
        return TestUser::query();
    }

    public function map($row): array
    {
        return [
            mb_strtoupper($row->name),
            $row->created_at->format('Y-m'),
            $row->active ? 'Active' : 'Inactive'
        ];
    }
}
