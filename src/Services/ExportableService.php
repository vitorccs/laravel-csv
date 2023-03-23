<?php

namespace Vitorccs\LaravelCsv\Services;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\FromCollection;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Vitorccs\LaravelCsv\Concerns\FromQueryCursor;
use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Exceptions\InvalidCellValueException;
use Vitorccs\LaravelCsv\Handlers\ArrayHandler;
use Vitorccs\LaravelCsv\Handlers\StreamHandler;
use Vitorccs\LaravelCsv\Helpers\CsvHelper;
use Vitorccs\LaravelCsv\Jobs\CreateCsv;

class ExportableService
{
    /**
     * @var CsvConfig
     */
    private CsvConfig $config;

    /**
     * @param CsvConfig $config
     */
    public function __construct(CsvConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return CsvConfig
     */
    public function getConfig(): CsvConfig
    {
        return $this->config;
    }

    /**
     * @param CsvConfig $config
     */
    public function setConfig(CsvConfig $config): void
    {
        $this->config = $config;
    }

    /**
     * @param object $exportable
     * @return int
     */
    public function count(object $exportable): int
    {
        if ($exportable instanceof FromArray) {
            return count($exportable->array());
        }

        if ($exportable instanceof FromCollection) {
            return $exportable->collection()->count();
        }

        if ($exportable instanceof FromQuery || $exportable instanceof FromQueryCursor) {
            return $exportable->query()->count();
        }

        return 0;
    }

    /**
     * @param object $exportable
     * @param string $filename
     * @param string|null $disk
     * @param array $diskOptions
     * @return string|null
     * @throws InvalidCellValueException
     */
    public function store(object  $exportable,
                          string  $filename,
                          ?string $disk = null,
                          array   $diskOptions = []): ?string
    {
        $success = Storage::disk($disk ?: $this->config->disk)->put(
            $filename,
            $this->getStream($exportable),
            $diskOptions
        );

        return $success ? $filename : null;
    }

    /**
     * @param object $exportable
     * @param string $filename
     * @return StreamedResponse
     * @throws InvalidCellValueException
     */
    public function download(object $exportable, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type' => CsvHelper::$contentType,
            'Content-Encoding' => 'none',
            'Content-Description' => 'File Transfer'
        ];

        $stream = $this->getStream($exportable);

        return Response::streamDownload(
            function () use ($stream) {
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            },
            $filename,
            $headers
        );
    }

    /**
     * @param object $exportable
     * @param string $filename
     * @param string|null $disk
     * @param array $diskOptions
     * @return PendingDispatch
     */
    public function queue(object  $exportable,
                          string  $filename,
                          ?string $disk = null,
                          array   $diskOptions = []): PendingDispatch
    {

        $job = new CreateCsv($exportable, $filename, $disk, $diskOptions);

        $job->timeout = $this->config->job_timeout;
        $job->tries = $this->config->job_attempts;
        $delay = now()->addSeconds($this->config->job_delay);

        return dispatch($job)->delay($delay);
    }

    /**
     * @param object $exportable
     * @return array
     * @throws InvalidCellValueException
     */
    public function array(object $exportable): array
    {
        return $this->getArray($exportable);
    }

    /**
     * @param object $exportable
     * @return resource
     * @throws InvalidCellValueException
     */
    private function getStream(object $exportable)
    {
        $stream = App::make(Writer::class, [
            'formatter' => new FormatterService($this->config),
            'handler' => new StreamHandler($this->config)
        ])->generate($exportable);

        rewind($stream);

        return $stream;
    }

    /**
     * @param object $exportable
     * @return array
     * @throws InvalidCellValueException
     */
    private function getArray(object $exportable): array
    {
        return App::make(Writer::class, [
            'formatter' => new FormatterService($this->config),
            'handler' => new ArrayHandler()
        ])->generate($exportable);
    }
}
