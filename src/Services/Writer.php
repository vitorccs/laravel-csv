<?php

namespace Vitorccs\LaravelCsv\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vitorccs\LaravelCsv\Concerns\FromArray;
use Vitorccs\LaravelCsv\Concerns\FromCollection;
use Vitorccs\LaravelCsv\Concerns\FromQuery;
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
        if ($rows instanceof Collection) {
            $rows = iterator_to_array($rows->values());
        }

        if ($exportable instanceof WithMapping) {
            $rows = array_map(fn($row) => $exportable->map($row), $rows);
        }

        $rows = array_map(function ($row) {
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
        }, $rows);

        $rows = array_map(function ($row, int $iRow) use ($exportable) {
            return array_map(function ($column, int $iColumn) use ($exportable, $iRow) {
                $formats = $exportable instanceof WithColumnFormatting
                    ? $exportable->columnFormats()
                    : [];
                $columnLetter = CsvHelper::getColumnLetter($iColumn + 1);
                $format = $formats[$columnLetter] ?? null;

                if ($format === CellFormat::DATE) {

                    $column = $this->formatter->date($column);
                }

                if ($format === CellFormat::DATETIME) {
                    $column = $this->formatter->datetime($column);
                }

                if ($format === CellFormat::DECIMAL) {
                    $column = $this->formatter->decimal($column);
                }

                if ($format === CellFormat::INTEGER) {
                    $column = $this->formatter->integer($column);
                }

                try {
                    if (!is_string($column)) {
                        $column = (string)$column;
                    }
                } catch (\Throwable $e) {
                    throw new InvalidCellValueException("{$columnLetter}{$iRow}");
                }

                return $column;
            }, $row, array_keys($row));
        }, $rows, array_keys($rows));

        foreach ($rows as $row) {
            $this->writeRow($row);
        }
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
