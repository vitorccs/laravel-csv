<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;

$factory->define(TestUser::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'email' => $faker->unique()->safeEmail(),
        'email_verified_at' => now(),
        'active' => 1
    ];
});
