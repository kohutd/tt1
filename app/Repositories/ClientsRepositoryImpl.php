<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ClientsRepositoryImpl extends AbstractRepository implements ClientsRepository
{
    public function create(array $attributes): Client
    {
        $client = new Client();

        $client->client_name = $attributes["name"];
        $client->address1 = $attributes["address1"];
        $client->address2 = $attributes["address2"];
        $client->city = $attributes["city"];
        $client->state = $attributes["state"];
        $client->country = $attributes["country"];
        $client->zip = $attributes["zipCode"];

        $client->latitude = $attributes["latitude"];
        $client->longitude = $attributes["longitude"];

        $client->phone_no1 = $attributes["phoneNo1"];
        $client->phone_no2 = $attributes["phoneNo2"];

        $client->status = $attributes["status"];

        $client->start_validity = $attributes["start_validity"];
        $client->end_validity = $attributes["end_validity"];

        $client->save();

        return $client;
    }

    public function getAll(array $with = []): Collection
    {
        return Client::with($with)->get();
    }


    public function getPage(int $page = 1,
                            int $perPage = 10,
                            string $orderBy = "id",
                            string $order = "desc",
                            array $filters = [],
                            array $with = []): LengthAwarePaginator
    {
        return $this->buildPageQuery(
            Client::class,
            $page,
            $perPage,
            $orderBy,
            $order,
            $filters,
            $with
        );
    }
}
