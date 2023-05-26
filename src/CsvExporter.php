<?php

namespace Vitorccs\LaravelCsv;

use Illuminate\Foundation\Bus\PendingDispatch;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Exceptions\InvalidCellValueException;
use Vitorccs\LaravelCsv\Services\ExportableService;

class CsvExporter
{
    /**
     * @var ExportableService
     */
    private ExportableService $service;

    /**
     * @param ExportableService $service
     */
    public function __construct(ExportableService $service)
    {
        $this->service = $service;
    }

    /**
     * @return CsvConfig
     */
    public function getConfig(): CsvConfig
    {
        return $this->service->getConfig();
    }

    /**
     * @param CsvConfig $config
     */
    public function setConfig(CsvConfig $config): void
    {
        $this->service->setConfig($config);
    }

    /**
     * @param object $exportable
     * @return int
     */
    public function count(object $exportable): int
    {
        return $this->service->count($exportable);
    }

    /**
     * @param object $exportable
     * @return array
     * @throws InvalidCellValueException
     */
    public function toArray(object $exportable): array
    {
        return $this->service->array($exportable);
    }

    /**
     * @param object $exportable
     * @param string $filename
     * @param string|null $disk
     * @param array $diskOptions
     * @return string
     * @throws InvalidCellValueException
     */
    public function store(object  $exportable,
                          string  $filename,
                          ?string $disk = null,
                          array   $diskOptions = []): string
    {
        return $this->service->store($exportable, $filename, $disk, $diskOptions);
    }

    /**
     * @param object $exportable
     * @param string $filename
     * @return StreamedResponse
     * @throws InvalidCellValueException
     */
    public function download(object $exportable, string $filename): StreamedResponse
    {
        return $this->service->download($exportable, $filename);
    }

    /**
     * @param  object  $exportable
     * @param  bool  $asString
     *
     * @throws InvalidCellValueException
     */
    public function stream(object $exportable, bool $asString = false)
    {
        $streamResponse = $this->service->stream($exportable);

        if ($asString) {
            return stream_get_contents($streamResponse);
        }

        return $streamResponse;
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
        return $this->service->queue($exportable, $filename, $disk, $diskOptions);
    }
}
