<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UsersRepository
{
    public function create(array $attributes, Client $client): User;

    public function getAll(): Collection;

    public function getPage(int $page = 1,
                            int $perPage = 10,
                            string $orderBy = "id",
                            string $order = "desc",
                            array $filters = [],
                            array $with = []): LengthAwarePaginator;
}
