<?php

namespace Vitorccs\LaravelCsv\Helpers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;

class QueryBuilderHelper
{
    /**
     * Chunk results in smaller parts and feeds into a callable
     *
     * NOTE: This function is similar to Laravel's chunk method with the
     * difference that is able to limit the total quantity of records
     *
     * @param Builder|EloquentBuilder $builder
     * @param int $size
     * @param int $count
     * @param callable $callback
     * @param int|null $limit
     * @return void
     */
    public static function chunk($builder,
                                 int $size,
                                 int $count,
                                 ?int $limit,
                                 callable $callback): void
    {
        $records = min($count, $limit ?: $count);
        $pages = (int)ceil($records / $size);

        for ($page = 0; $page < $pages; $page++) {
            $remaining = $records - ($page * $size);
            $take = min($remaining, $size);

            $rows = $builder
                ->offset($page * $size)
                ->take($take)
                ->get();

            $callback($rows);
        }
    }
}
