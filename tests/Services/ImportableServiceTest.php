<?php

namespace Vitorccs\LaravelCsv\Tests\Services;

use Vitorccs\LaravelCsv\Services\ImportableService;
use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestUsersSeeder;
use Vitorccs\LaravelCsv\Tests\TestCase;

class ImportableServiceTest extends TestCase
{
    protected ImportableService $service;
    protected string $disk;
    protected array $diskOptions;
    protected array $expected;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestUsersSeeder::class);

        $this->service = app(ImportableService::class);
        $this->disk = 'samples';
        $this->diskOptions = ['option' => 'value'];

        $this->expected = [
            ["A 1","B 1","C 1"],
            ["A 2","B 2","C 2"],
            ["A 3","B 3","C 3"]
        ];
    }

    public function test_from_disk()
    {
        $contents = $this->service->fromDisk('import_test.csv', $this->disk);

        $this->assertSame($contents, $this->expected);
    }
}
