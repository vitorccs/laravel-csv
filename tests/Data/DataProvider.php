<?php

namespace Vitorccs\LaravelCsv\Tests\Data;

use Carbon\Carbon;

trait DataProvider
{
    protected string $date = 'Y-m-d';
    protected string $datetime = 'Y-m-d H:i:s';
    protected int $decimals = 2;
    protected string $decimalSep = '.';
    protected string $thousandSep = ',';

    /**
     * @return array[]
     */
    public function valid_dates(): array
    {
        return [
            'valid carbon' => [
                Carbon::create(2021, 12, 31, 21, 45, 55, 'UTC'),
                '2021-12-31'
            ],
            'valid datetime' => [
                \Datetime::createFromFormat('Y-m-d H:i:s', '2021-12-31 21:45:55'),
                '2021-12-31'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function valid_datetimes(): array
    {
        return [
            'valid carbon' => [
                Carbon::create(2021, 12, 31, 21, 45, 55, 'UTC'),
                '2021-12-31 21:45:55'
            ],
            'valid datetime' => [
                \Datetime::createFromFormat('Y-m-d H:i:s', '2021-12-31 21:45:55'),
                '2021-12-31 21:45:55'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function invalid_dates(): array
    {
        return [
            'invalid string' => [
                'any',
                'any'
            ],
            'invalid integer' => [
                2,
                '2'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function valid_integers(): array
    {
        return [
            'valid int' => [
                2,
                '2'
            ],
            'valid float' => [
                3.40,
                '3'
            ],
            'valid string' => [
                '500',
                '500'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function valid_decimals(): array
    {
        return [
            'valid int' => [
                2,
                '2.00'
            ],
            'valid float' => [
                3.40,
                '3.40'
            ],
            'valid string' => [
                '500',
                '500.00'
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function invalid_numbers(): array
    {
        $carbon = now();

        return [
            'invalid string' => [
                'any',
                'any'
            ],
            'invalid carbon' => [
                $carbon,
                (string) $carbon
            ]
        ];
    }
}
