<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestUsersSeeder;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromCollectionExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromCursorExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class FromCollectionTest extends TestCase
{
    protected string $filename = 'from_collection.csv';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestUsersSeeder::class);
    }

    public function test_from_collection()
    {
        $export = new FromCollectionExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }

    public function test_from_cursor()
    {
        $export = new FromCursorExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertEquals($export->toArray(), $contents);
    }
}
