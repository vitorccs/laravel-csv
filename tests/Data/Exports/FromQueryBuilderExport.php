<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Illuminate\Support\Facades\DB;

class FromQueryBuilderExport implements FromQuery
{
    use Exportable;

    public function query()
    {
        return DB::table('test_users');
    }
}
