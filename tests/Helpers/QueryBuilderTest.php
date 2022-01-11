<?php

namespace Vitorccs\LaravelCsv\Tests\Helpers;

use Illuminate\Support\Collection;
use Vitorccs\LaravelCsv\Helpers\QueryBuilderHelper;
use Vitorccs\LaravelCsv\Tests\Data\Database\Seeders\TestUsersSeeder;
use Vitorccs\LaravelCsv\Tests\Data\Stubs\TestUser;
use Vitorccs\LaravelCsv\Tests\TestCase;

class QueryBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestUsersSeeder::class);
    }

    public function test_from_query()
    {
        $builder = TestUser::query();
        $chunks = [];
        $chunkSize = 9;
        $countResults = $builder->count();

        QueryBuilderHelper::chunk(
            $builder,
            $chunkSize,
            $countResults,
            null,
            function (Collection $collection) use (&$chunks) {
                $chunks[] = $collection->pluck('id')->toArray();
            }
        );

        $countChunks = ceil($countResults / $chunkSize);

        $userIds = $builder
            ->make()
            ->get()
            ->pluck('id')
            ->toArray();

        $flattenUserIds = collect($chunks)
            ->flatten()
            ->toArray();

        $this->assertCount($countChunks, $chunks);
        $this->assertEquals($userIds, $flattenUserIds);
    }
}
