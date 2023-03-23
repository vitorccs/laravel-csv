<?php

namespace Vitorccs\LaravelCsv\Concerns;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

interface FromQueryCursor
{
    /**
     * @return Builder|EloquentBuilder
     */
    public function query();
}