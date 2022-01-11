<?php

namespace Vitorccs\LaravelCsv\Services;

use Illuminate\Support\Facades\Storage;
use Vitorccs\LaravelCsv\Entities\CsvConfig;

class ImportableService
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
     * @param CsvConfig $config
     */
    public function setConfig(CsvConfig $config): void
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
     * @param string $filename
     * @param string|null $disk
     * @return array
     */
    public function fromDisk(string $filename, ?string $disk = null): array
    {
        $filename = Storage::disk($disk ?: $this->config->disk)
            ->path($filename);

        return $this->getReader()->generate($filename);
    }

    /**
     * @return Reader
     */
    public function getReader(): Reader
    {
        return app(Reader::class, [
            'csvConfig' => $this->config
        ]);
    }
}
