<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UsersRepositoryImpl extends AbstractRepository implements UsersRepository
{
    public function create(array $attributes, Client $client): User
    {
        $user = new User();

        $user->first_name = $attributes['firstName'];
        $user->last_name = $attributes['lastName'];
        $user->email = $attributes['email'];
        $user->password = $attributes['password'];
        $user->phone = $attributes['phone'];
        $user->last_password_reset = Carbon::now();

        $user->client()->associate($client);
        $user->save();

        return $user;
    }

    public function getAll(array $with = []): Collection
    {
        return User::with($with)->get();
    }

    public function getPage(int $page = 1,
                            int $perPage = 10,
                            string $orderBy = "id",
                            string $order = "desc",
                            array $filters = [],
                            array $with = []): LengthAwarePaginator
    {
        return $this->buildPageQuery(
            User::class,
            $page,
            $perPage,
            $orderBy,
            $order,
            $filters,
            $with
        );
    }
}
