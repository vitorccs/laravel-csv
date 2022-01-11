<?php

namespace Vitorccs\LaravelCsv\Concerns;

use Illuminate\Foundation\Bus\PendingDispatch;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vitorccs\LaravelCsv\Facades\CsvExporter;
use Vitorccs\LaravelCsv\Helpers\CsvHelper;

trait Exportable
{
    /**
     * @return int|null
     */
    public function limit(): ?int
    {
        return null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return CsvExporter::count($this);
    }

    /**
     * @returns array
     */
    public function toArray(): array
    {
        return CsvExporter::toArray($this);
    }

    /**
     * @param string|null $filename
     * @param string|null $disk
     * @param array $diskOptions
     * @return string
     */
    public function store(?string $filename = null,
                          ?string $disk = null,
                          array   $diskOptions = []): string
    {
        return CsvExporter::store($this, $this->getFilename($filename), $disk, $diskOptions);
    }

    /**
     * @param string|null $filename
     * @return StreamedResponse
     */
    public function download(?string $filename = null): StreamedResponse
    {
        return CsvExporter::download($this, $this->getFilename($filename));
    }

    /**
     * @param string|null $filename
     * @param string|null $disk
     * @param array $diskOptions
     * @return PendingDispatch
     */
    public function queue(?string $filename = null,
                          ?string $disk = null,
                          array   $diskOptions = []): PendingDispatch
    {
        return CsvExporter::queue($this, $this->getFilename($filename), $disk, $diskOptions);
    }

    /**
     * @param string|null $filename
     * @return string
     */
    public function getFilename(?string $filename = null): string
    {
        return $filename ?: CsvHelper::filename();
    }
}
