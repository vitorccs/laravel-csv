<?php

namespace Vitorccs\LaravelCsv\Helpers;

use Illuminate\Database\Eloquent\Model;

class ModelHelper
{
    /**
     * Return an array of all model property values
     *
     * NOTE: Laravel $model->toArray() could not be used since
     * it casts the properties values which unable us to perform
     * formatting functions
     *
     * @param Model $model
     * @return array
     */
    public static function toArrayValues(Model $model): array {
        return array_reduce(
            array_keys($model->getAttributes()),
            function (array $acc, string $attribute) use ($model) {
                $acc[] = $model[$attribute];
                return $acc;
            },
            []
        );
    }
}
