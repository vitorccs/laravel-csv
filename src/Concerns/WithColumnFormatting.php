<?php

namespace Vitorccs\LaravelCsv\Concerns;

interface WithColumnFormatting
{
    /**
     * @return array
     */
    public function columnFormats(): array;
}
