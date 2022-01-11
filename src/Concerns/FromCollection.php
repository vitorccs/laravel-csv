<?php

namespace Vitorccs\LaravelCsv\Concerns;

use Illuminate\Support\Collection;

interface FromCollection
{
    /**
     * @return Collection
     */
    public function collection(): Collection;
}
