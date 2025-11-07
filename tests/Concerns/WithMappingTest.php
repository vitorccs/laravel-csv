<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestCsvSeeder;
use Vitorccs\LaravelCsv\Tests\Data\Exports\WithMappingExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\WithMappingMultipleRowsExport;
use Vitorccs\LaravelCsv\Tests\Data\Imports\WithMappingImport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class WithMappingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestCsvSeeder::class);
    }

    public function test_export_mapping()
    {
        $export = new WithMappingExport();

        $export->store($this->filename);
        $actual = $this->getFromDisk($this->filename);

        $this->assertSame($export->expected(), $actual);
    }

    public function test_import_mapping()
    {
        $import = new WithMappingImport();

        $rows = $import->getArray();
        $expected = $import->expected();

        $this->assertSame($rows, $expected);
    }

    public function test_export_mapping_multiple_rows()
    {
        $export = new WithMappingMultipleRowsExport();

        $export->store($this->filename);
        $actual = $this->getFromDisk($this->filename);

        $this->assertSame($export->expected(), $actual);
    }
}
