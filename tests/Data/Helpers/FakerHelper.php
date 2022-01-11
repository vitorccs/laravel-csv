<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Helpers;

use Faker\Factory;
use Faker\Generator;

class FakerHelper
{
    /**
     * @var Generator|null
     */
    protected static ?Generator $faker = null;

    /**
     * @return Generator
     */
    public static function get(): Generator
    {
        if (is_null(self::$faker)) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }
}
