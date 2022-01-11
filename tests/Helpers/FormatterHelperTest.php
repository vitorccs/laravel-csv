<?php

namespace Vitorccs\LaravelCsv\Tests\Helpers;

use Vitorccs\LaravelCsv\Tests\TestCase;
use Vitorccs\LaravelCsv\Tests\Data\DataProvider;
use Vitorccs\LaravelCsv\Helpers\FormatterHelper;

class FormatterHelperTest extends TestCase
{
    use DataProvider;

    /**
     * @dataProvider valid_dates
     * @dataProvider valid_datetimes
     * @dataProvider invalid_dates
     */
    public function test_format_dates($date, string $formatted): void
    {
        $this->assertSame(
            $formatted,
            FormatterHelper::date($date, $this->datetime),
        );
    }

    /**
     * @dataProvider valid_decimals
     * @dataProvider invalid_numbers
     */
    public function test_format_number($number, string $formatted): void
    {
        $this->assertSame(
            $formatted,
            FormatterHelper::number($number, $this->decimals, $this->decimalSep, $this->thousandSep),
        );
    }
}
