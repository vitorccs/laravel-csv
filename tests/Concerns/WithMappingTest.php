<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestUsersSeeder;
use Vitorccs\LaravelCsv\Tests\Data\Exports\WithMappingExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class WithMappingTest extends TestCase
{
    protected string $filename = 'with_mapping_test.csv';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestUsersSeeder::class);
    }

    public function test_from_query()
    {
        $export = new WithMappingExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }
}
