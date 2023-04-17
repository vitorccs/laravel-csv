<?php

namespace Vitorccs\LaravelCsv\Tests\Services;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Jobs\CreateCsv;
use Vitorccs\LaravelCsv\Services\ExportableService;
use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestUsersSeeder;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromArrayExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromCollectionExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromCursorExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromEloquentBuilderExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\FromQueryBuilderExport;
use Vitorccs\LaravelCsv\Tests\Data\Exports\WithMappingExportSimple;
use Vitorccs\LaravelCsv\Tests\Data\Helpers\FakerHelper;
use Vitorccs\LaravelCsv\Tests\TestCase;

class ExportableServiceTest extends TestCase
{
    protected ExportableService $service;
    protected string $disk;
    protected array $diskOptions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestUsersSeeder::class);

        $this->service = app(ExportableService::class);
        $this->disk = 'local';
        $this->diskOptions = ['option' => 'value'];
    }

    public function test_count()
    {
        $arrayExport = new FromArrayExport();
        $collectionExport = new FromCollectionExport();
        $databaseExports = [
            new FromEloquentBuilderExport(),
            new FromQueryBuilderExport(),
            new FromCursorExport()
        ];

        $this->assertSame(
            count($arrayExport->array()),
            $this->service->count($arrayExport)
        );

        $this->assertSame(
            $collectionExport->collection()->count(),
            $this->service->count($collectionExport)
        );

        foreach ($databaseExports as $export) {
            $this->assertSame(
                TestUsersSeeder::$amount,
                $this->service->count($export)
            );
        }
    }

    public function test_limit()
    {
        $exports = [
            $this->getExportMock(FromArrayExport::class),
            $this->getExportMock(FromCollectionExport::class),
            $this->getExportMock(FromCursorExport::class),
            $this->getExportMock(FromQueryBuilderExport::class),
            $this->getExportMock(FromEloquentBuilderExport::class),
        ];

        foreach ($exports as $export) {
            $this->assertSame(
                $export->limit(),
                count($this->service->array($export))
            );
        }
    }

    public function test_queue()
    {
        $filename = FakerHelper::get()->word();

        Bus::fake();

        $this->service->queue(
            new FromArrayExport(),
            $filename,
            $this->disk,
            $this->diskOptions
        );

        Bus::assertDispatched(function (CreateCsv $job) use ($filename) {
            return $job->filename === $filename &&
                $job->disk === $this->disk &&
                $job->diskOptions === $this->diskOptions;
        });
    }

    public function test_array()
    {
        $export = new WithMappingExportSimple();

        $results = $this->service->array($export);

        $fromLaravel = $export->query()
            ->get()
            ->map(fn($user) => [$user->id, $user->name])
            ->toArray();

        $this->assertEquals($results, $fromLaravel);
    }

    public function test_store()
    {
        $filename = FakerHelper::get()->word();

        Storage::shouldReceive('disk->put')
            ->once()
            ->andReturns($filename);

        $response = $this->service->store(
            new WithMappingExportSimple(),
            $filename,
            $this->disk,
            $this->diskOptions
        );

        $this->assertEquals($response, $filename);
    }

    public function test_download()
    {
        $filename = FakerHelper::get()->word();

        Response::shouldReceive('streamDownload')
            ->once()
            ->andReturns(\Mockery::mock(StreamedResponse::class));

        $response = $this->service->download(
            new WithMappingExportSimple(),
            $filename
        );

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }

    public function test_set_config()
    {
        $export = new FromArrayExport();

        $csvConfig = new CsvConfig();
        $csvConfig->csv_delimiter = '|';
        $csvConfig->csv_enclosure = "'";
        $this->service->setConfig($csvConfig);

        $filename = 'test_config.csv';
        $this->service->store($export, $filename);
        $contents = $this->readFromDisk($filename, $csvConfig);

        $this->assertEquals($contents, $export->array());
    }

    private function getExportMock(string $abstractClass, int $limit = 5)
    {
        $mock = $this->getMockForAbstractClass(
            $abstractClass,
            [],
            '',
            true,
            true,
            true,
            ['limit']
        );

        $mock->method('limit')
            ->willReturn($limit);

        return $mock;
    }
}
