<?php

namespace Vitorccs\LaravelCsv\Handlers;

use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Helpers\CsvHelper;

class StreamHandler implements Handler
{
    /**
     * @var resource
     */
    protected $stream;

    /**
     * @var CsvConfig
     */
    private CsvConfig $csvConfig;

    /**
     *
     */
    public function __construct(CsvConfig $csvConfig)
    {
        $this->stream = fopen('php://temp', 'a+');
        $this->csvConfig = $csvConfig;
        $this->init();
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->stream;
    }

    /**
     * @param array $content
     * @return void
     */
    public function addContent(array $content): void
    {
        fputcsv(
            $this->stream,
            $content,
            $this->csvConfig->csv_delimiter,
            $this->csvConfig->csv_enclosure,
            $this->csvConfig->csv_escape
        );
    }

    /**
     * @return void
     */
    private function init(): void
    {
        if ($this->csvConfig->csv_bom) {
            fwrite($this->stream, CsvHelper::getBom());
        }
    }
}
