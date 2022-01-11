<?php

namespace Vitorccs\LaravelCsv;

use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Services\ImportableService;

class CsvImporter
{
    /**
     * @var ImportableService
     */
    private ImportableService $service;

    /**
     * @param ImportableService $service
     */
    public function __construct(ImportableService $service)
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
     * @param string $filename
     * @param string|null $disk
     * @return array
     */
    public function fromDisk(string $filename, ?string $disk = null): array
    {
        return $this->service->fromDisk($filename, $disk);
    }
}
