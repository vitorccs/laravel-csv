<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Exports\FromCollectionExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class FromCollectionTest extends TestCase
{
    protected string $filename = 'from_collection.csv';

    public function test_from_collection()
    {
        $export = new FromCollectionExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }
}
