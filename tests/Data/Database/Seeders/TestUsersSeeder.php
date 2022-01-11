<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Database\Seeders;

use Illuminate\Database\Seeder;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;

class TestUsersSeeder extends Seeder
{
    public static int $amount = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(TestUser::class, static::$amount)->create();
    }
}
