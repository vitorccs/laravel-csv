<?php

namespace Vitorccs\LaravelCsv\Tests\Concerns;

use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestUsersSeeder;
use Vitorccs\LaravelCsv\Tests\Data\Exports\WithLimitExport;
use Vitorccs\LaravelCsv\Tests\TestCase;

class WithLimitTest extends TestCase
{
    protected string $filename = 'with_limit.csv';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestUsersSeeder::class);
    }

    public function test_from_query()
    {
        $export = new WithLimitExport();

        $export->store($this->filename);
        $contents = $this->readFromDisk($this->filename);

        $this->assertCount($export->limit(), $contents);
    }
}
