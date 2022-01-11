<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Exports\FromArrayExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class FromArrayTest extends TestCase
{
    protected string $filename = 'from_array.csv';

    public function test_from_array()
    {
        $export = new FromArrayExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }
}
