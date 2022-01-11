<?php

namespace Vitorccs\LaravelCsv\Tests\Services;

use Vitorccs\LaravelCsv\Tests\TestCase;
use Vitorccs\LaravelCsv\Tests\Data\DataProvider;
use Vitorccs\LaravelCsv\Services\FormatterService;

class FormatterServiceTest extends TestCase
{
    use DataProvider;

    /**
     * @dataProvider valid_dates
     * @dataProvider invalid_dates
     */
    public function test_format_dates($date, string $formatted): void
    {
        $service = app(FormatterService::class);

        $this->assertSame(
            $formatted,
            $service->date($date),
        );
    }

    /**
     * @dataProvider valid_datetimes
     * @dataProvider invalid_dates
     */
    public function test_format_datetime($date, string $formatted): void
    {
        $service = app(FormatterService::class);

        $this->assertSame(
            $formatted,
            $service->datetime($date),
        );
    }

    /**
     * @dataProvider valid_decimals
     * @dataProvider invalid_numbers
     */
    public function test_format_decimal($number, string $formatted): void
    {
        $service = app(FormatterService::class);

        $this->assertSame(
            $service->decimal($number),
            $formatted
        );
    }

    /**
     * @dataProvider valid_integers
     * @dataProvider invalid_numbers
     */
    public function test_format_integer($number, string $formatted): void
    {
        $service = app(FormatterService::class);

        $this->assertSame(
            $service->integer($number),
            $formatted
        );
    }
}
