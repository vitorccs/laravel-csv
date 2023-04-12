<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;

class FromEloquentBuilderExport implements FromQuery
{
    use Exportable;

    public function query()
    {
        return TestUser::query();
    }
}
