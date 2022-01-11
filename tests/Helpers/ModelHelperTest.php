<?php

namespace Vitorccs\LaravelCsv\Tests\Helpers;

use Vitorccs\LaravelCsv\Helpers\ModelHelper;
use Vitorccs\LaravelCsv\Tests\Data\Helpers\FakerHelper;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\MockModel;
use Vitorccs\LaravelCsv\Tests\TestCase;

class ModelHelperTest extends TestCase
{
    /**
     * @dataProvider mockProperties
     */
    public function test_array_values(array $properties)
    {
        $model = new MockModel();
        foreach ($properties as $property => $value) {
            $model->{$property} = $value;
        }

        $arrayValues = ModelHelper::toArrayValues($model);

        $this->assertSame($arrayValues, array_values($properties));
    }

    /**
     * @return array[]
     */
    public function mockProperties(): array
    {
        return [
            'valid' => [
                [
                    'intProp' => FakerHelper::get()->numberBetween(),
                    'dateProp' => FakerHelper::get()->dateTime(),
                    'strProp' => FakerHelper::get()->text()
                ]
            ]
        ];
    }
}
