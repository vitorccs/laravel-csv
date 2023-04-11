<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Exports;

use Vitorccs\LaravelCsv\Concerns\Exportable;
use Vitorccs\LaravelCsv\Concerns\FromQueryCursor;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;

class FromQueryCursorExport implements FromQueryCursor
{
    use Exportable;

    public function query()
    {
        return TestUser::query();
    }
}