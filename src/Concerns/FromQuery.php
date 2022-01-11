<?php

namespace Vitorccs\LaravelCsv\Concerns;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

interface FromQuery
{
    /**
     * @return Builder|EloquentBuilder
     */
    public function query();
}
