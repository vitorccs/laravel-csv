<?php

namespace Vitorccs\LaravelCsv\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\FromCollection;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
use Vitorccs\LaravelCsv\Concerns\FromQueryCursor;
use Vitorccs\LaravelCsv\Concerns\WithColumnFormatting;
use Vitorccs\LaravelCsv\Concerns\WithHeadings;
use Vitorccs\LaravelCsv\Concerns\WithMapping;
use Vitorccs\LaravelCsv\Enum\CellFormat;
use Vitorccs\LaravelCsv\Exceptions\InvalidCellValueException;
use Vitorccs\LaravelCsv\Handlers\Handler;
use Vitorccs\LaravelCsv\Helpers\CsvHelper;
use Vitorccs\LaravelCsv\Helpers\ModelHelper;
use Vitorccs\LaravelCsv\Helpers\QueryBuilderHelper;

class Writer
{
    /**
     * @var FormatterService
     */
    private FormatterService $formatter;

    /**
     * @var Handler
     */
    private Handler $handler;

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
            $this->iterateRows($exportable, $rows);
        }

        if ($exportable instanceof FromCollection) {
            $rows = $exportable->collection();
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

        if ($exportable instanceof FromQueryCursor) {
            $this->iterateRows($exportable, $exportable->query()->cursor());
        }

        return $this->handler->getResource();
    }

    /**
     * @param object $exportable
     * @param Collection|array $rows
     * @return void
     * @throws InvalidCellValueException
     */
    private function iterateRows(object $exportable, $rows): void
    {
        $formats = $exportable instanceof WithColumnFormatting ? $exportable->columnFormats() : [];
        $withMapping = $exportable instanceof WithMapping;
        $row_index = 1;
        foreach ($rows as $row) {
            $mappedRow = $withMapping ? $exportable->map($row) : $row;
            $normalizedRow = $this->normalizeRow($mappedRow);
            $formattedRow = $this->applyFormatting($normalizedRow, $formats, $row_index++);
            $this->writeRow($formattedRow);
        }
    }

    private function normalizeRow($row): array
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
     * @throws InvalidCellValueException
     */
    private function applyFormatting(array $row, array $formats, int $row_index): array
    {
        return array_map(
            fn ($value, int $column_index) => $this->formatCellValue($value, $formats, $row_index, $column_index),
            $row,
            array_keys($row)
        );
    }

    /**
     * @throws InvalidCellValueException
     */
    private function formatCellValue($value, array $formats, int $row_index, int $column_index): string
    {
        $columnLetter = CsvHelper::getColumnLetter($column_index + 1);
        $format = $formats[$columnLetter] ?? null;

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
            if (! is_string($value)) {
                return (string)$value;
            }
        } catch (Throwable $e) {
            throw new InvalidCellValueException("{$columnLetter}{$row_index}");
        }

        return $value;
    }

    /**
     * @param array $content
     * @return void
     */
    private function writeRow(array $content): void
    {
        $this->handler->addContent($content);
    }
}
