<?php

namespace Vitorccs\LaravelCsv\Tests\Entities;

use Vitorccs\LaravelCsv\Entities\CsvConfig;
use Vitorccs\LaravelCsv\Tests\TestCase;

class CsvConfigTest extends TestCase
{
    public function test_instance()
    {
        $config = config('csv');
        $instance = new CsvConfig();

        foreach ($config as $prop => $value) {
            $this->assertEquals($instance->{$prop} ?? null, $value);
        }
    }
}
