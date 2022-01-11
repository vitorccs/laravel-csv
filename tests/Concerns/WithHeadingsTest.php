<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Exports\WithHeadingsExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class WithHeadingsTest extends TestCase
{
    protected string $filename = 'with_headings.csv';

    public function test_with_headings()
    {
        $export = new WithHeadingsExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }
}
