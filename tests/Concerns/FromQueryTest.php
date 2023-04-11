<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestUsersSeeder;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromEloquentBuilderExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromQueryBuilderExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class FromQueryTest extends TestCase
{
    protected string $filename = 'from_query.csv';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestUsersSeeder::class);
    }

    public function test_from_eloquent_builder()
    {
        $export = new FromEloquentBuilderExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }

    public function test_from_query_builder()
    {
        $export = new FromQueryBuilderExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }
}
