<?php

namespace Vitorccs\LaravelCsv\Tests\Helpers;

use Vitorccs\LaravelCsv\Helpers\CsvHelper;
use Vitorccs\LaravelCsv\Tests\TestCase;

class CsvHelperTest extends TestCase
{
    /**
     * @dataProvider columnLetters
     */
    public function test_column_number(array $columns)
    {
        list($letter, $number) = $columns;

        $this->assertEquals(
            CsvHelper::getColumnLetter($number),
            $letter
        );
    }

    public function test_uuid_filename() {
        $this->assertMatchesRegularExpression(
            '/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/',
            CsvHelper::filename()
        );
    }

    /**
     * @return array
     */
    public function columnLetters(): array
    {
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        $tests = $letters;

        foreach ($letters as $outerLetter) {
            foreach ($letters as $innerLetter) {
                $tests[] = "{$outerLetter}{$innerLetter}";
            }
        }

        $tests = array_map(
            fn(string $letter, int $index) => [$letter, $index + 1],
            $tests,
            array_keys($tests)
        );

        return [
            $tests
        ];
    }
}
