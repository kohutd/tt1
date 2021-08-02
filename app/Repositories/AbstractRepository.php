<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class AbstractRepository
{
    protected function buildPageQuery(string $model,
                                      int $page = 1,
                                      int $perPage = 10,
                                      string $orderBy = "id",
                                      string $order = "desc",
                                      array $filters = [],
                                      array $with = []): LengthAwarePaginator
    {
        return $model::query()
            ->with($with)
            ->orderBy($orderBy, $order)
            ->where(function ($query) use ($filters) {
                foreach ($filters as $filter) {
                    // todo: validate attribute name
                    $query->where($filter[0], $filter[1], $filter[2]);
                }
            })
            ->paginate($perPage, ['*'], 'page', $page);
    }
}
