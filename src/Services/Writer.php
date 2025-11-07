<?php

namespace Vitorccs\LaravelCsv\Services;

use Illuminate\Database\Eloquent\Model;
use Vitorccs\LaravelCsv\Concerns\Exportables\FromArray;
use Vitorccs\LaravelCsv\Concerns\Exportables\FromCollection;
use Vitorccs\LaravelCsv\Concerns\Exportables\FromQuery;
use Vitorccs\LaravelCsv\Concerns\WithColumnFormatting;
use Vitorccs\LaravelCsv\Concerns\WithHeadings;
use Vitorccs\LaravelCsv\Concerns\WithMapping;
use Vitorccs\LaravelCsv\Enum\CellFormat;
use Vitorccs\LaravelCsv\Exceptions\InvalidCellValueException;
use Vitorccs\LaravelCsv\Handlers\Writers\Handler;
use Vitorccs\LaravelCsv\Helpers\CsvHelper;
use Vitorccs\LaravelCsv\Helpers\ModelHelper;
use Vitorccs\LaravelCsv\Helpers\QueryBuilderHelper;

class Writer
{
    /**
     * @var FormatterService
     */
    protected FormatterService $formatter;

    /**
     * @var Handler
     */
    protected Handler $handler;

    /**
     * @param Handler $handler
     * @param FormatterService $formatter
     */
    public function __construct(FormatterService $formatter, Handler $handler)
    {
        $this->formatter = $formatter;
        $this->handler = $handler;
    }

    /**
     * @param object $exportable
     * @return resource|array
     * @throws InvalidCellValueException
     */
    public function generate(object $exportable)
    {
        if ($exportable instanceof WithHeadings) {
            $this->writeRow($exportable->headings());
        }

        if ($exportable instanceof FromArray) {
            $rows = $exportable->array();
            if ($exportable->limit()) {
                $rows = array_splice($rows, 0, $exportable->limit());
            }
            $this->iterateRows($exportable, $rows);
        }

        if ($exportable instanceof FromCollection) {
            $rows = $exportable->collection();
            if ($exportable->limit()) {
                $rows = $rows->take($exportable->limit());
            }
            $this->iterateRows($exportable, $rows);
        }

        if ($exportable instanceof FromQuery) {
            QueryBuilderHelper::chunk(
                $exportable->query(),
                $this->formatter->config->chunk_size,
                $exportable->count(),
                $exportable->limit(),
                fn($rows) => $this->iterateRows($exportable, $rows),
            );
        }

        return $this->handler->getResource();
    }

    /**
     * @param object $exportable
     * @param iterable $rows
     * @return void
     * @throws InvalidCellValueException
     */
    protected function iterateRows(object   $exportable,
                                   iterable $rows): void
    {
        $formats = $exportable instanceof WithColumnFormatting ? $exportable->columnFormats() : [];
        $withMapping = $exportable instanceof WithMapping;

        foreach ($rows as $index => $row) {
            $mappedRow = $withMapping ? $exportable->map($row) : $row;

            $firstElement = is_array($mappedRow) ? reset($mappedRow) : null;
            
            if ($withMapping && is_array($firstElement)) {
                foreach ($mappedRow as $subRow) {
                    $this->processAndWriteRow($subRow, $formats, $index);
                }
            } else {
                $this->processAndWriteRow($mappedRow, $formats, $index);
            }
        }
    }

    /**
     * @param mixed $row
     * @return array
     */
    protected function normalizeRow(mixed $row): array
    {
        if ($row instanceof Model) {
            $row = ModelHelper::toArrayValues($row);
        }
        if (is_object($row)) {
            $row = (array)$row;
        }
        if (is_array($row)) {
            $row = array_values($row);
        }
        return $row;
    }

    /**
     * @param array $row
     * @param array $formats
     * @param int $rowIndex
     * @return array
     * @throws InvalidCellValueException
     */
    protected function applyFormatting(array $row,
                                       array $formats,
                                       int   $rowIndex): array
    {
        return array_map(
            fn($value, int $columnIndex) => $this->formatCellValue($value, $formats, $rowIndex, $columnIndex),
            $row,
            array_keys($row)
        );
    }

    /**
     * @throws InvalidCellValueException
     */
    protected function formatCellValue(mixed $value,
                                       array $formats,
                                       int   $rowIndex,
                                       int   $columnIndex): string
    {
        $columnLetter = CsvHelper::getColumnLetter($columnIndex + 1);
        $rowNumber = $rowIndex + 1;
        $format = $formats[$columnLetter] ?? null;

        if (is_null($value)) {
            return '';
        }

        if ($format === CellFormat::DATE) {
            return $this->formatter->date($value);
        }

        if ($format === CellFormat::DATETIME) {
            return $this->formatter->datetime($value);
        }

        if ($format === CellFormat::DECIMAL) {
            return $this->formatter->decimal($value);
        }

        if ($format === CellFormat::INTEGER) {
            return $this->formatter->integer($value);
        }

        try {
            if (!is_string($value)) {
                return (string)$value;
            }
        } catch (\Throwable $e) {
            throw new InvalidCellValueException("{$columnLetter}{$rowNumber}");
        }

        return $value;
    }

    /**
     * @param mixed $row
     * @param array $formats
     * @param int $rowIndex
     * @return void
     * @throws InvalidCellValueException
     */
    protected function processAndWriteRow(mixed $row, array $formats, int $rowIndex): void
    {
        $normalizedRow = $this->normalizeRow($row);
        $formattedRow = $this->applyFormatting($normalizedRow, $formats, $rowIndex);
        $this->writeRow($formattedRow);
    }

    /**
     * @param array $content
     * @return void
     */
    protected function writeRow(array $content): void
    {
        $this->handler->addContent($content);
    }
}
