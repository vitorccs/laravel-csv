<?php

namespace Vitorccs\LaravelCsv\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vitorccs\LaravelCsv\Exceptions\InvalidCellValueException;
use Vitorccs\LaravelCsv\Services\ExportableService;

class CreateCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * @var object
     */
    public object $exportable;

    /**
     * @var string
     */
    public string $filename;

    /**
     * @var string|null
     */
    public ?string $disk;

    /**
     * @var array
     */
    public array $diskOptions;

    /**
     * @param object $exportable
     * @param string $filename
     * @param string|null $disk
     * @param array $diskOptions
     */
    public function __construct(object  $exportable,
                                string  $filename,
                                ?string $disk = null,
                                array   $diskOptions = [])
    {
        $this->exportable = $exportable;
        $this->filename = $filename;
        $this->disk = $disk;
        $this->diskOptions = $diskOptions;
    }

    /**
     * @param ExportableService $exportableService
     * @return void
     * @throws InvalidCellValueException
     */
    public function handle(ExportableService $exportableService): void
    {
        $exportableService->store(
            $this->exportable,
            $this->filename,
            $this->disk,
            $this->diskOptions
        );
    }
}
