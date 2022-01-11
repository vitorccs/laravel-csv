<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Exports\WithColumnFormattingExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class WithColumnFormattingTest extends TestCase
{
    protected string $filename = 'with_column_formatting.csv';

    public function teste_with_column_formatting()
    {
        $export = new WithColumnFormattingExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }
}
