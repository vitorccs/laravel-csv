<?php

namespace Vitorccs\LaravelCsv\Concerns;

interface WithHeadings
{
    /**
     * @return string[]
     */
    public function headings(): array;
}
