<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Http\Resources\AccountResource;
use App\Repositories\ClientsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected array $columnsMap = [
        "id" => "id",
        "name" => "client_name",
        "address1" => "address1",
        "address2" => "address2",
        "city" => "city",
        "state" => "state",
        "country" => "country",
        "zipCode" => "zip",
        "latitude" => "latitude",
        "longitude" => "longitude",
        "phoneNo1" => "phone_no1",
        "phoneNo2" => "phone_no2",
        "startValidity" => "start_validity",
        "endValidity" => "end_validity",
        "status" => "status",
    ];

    protected ClientsRepository $clientsRepository;

    public function __construct(ClientsRepository $clientsRepository)
    {
        $this->clientsRepository = $clientsRepository;
    }

    public function getAll(Request $request): JsonResponse
    {
        $page = $request->get("page", 1);
        $perPage = $request->get("perPage", 10);
        $order = $request->get("order", "desc");
        $orderBy = $request->get("orderBy", "id");
        $filter = $request->get("filter");

        $orderByColumn = $this->mapClientFieldToColumn($orderBy);
        $filters = $this->parseFilterField($filter);

        $pageResult = $this->clientsRepository->getPage(
            $page,
            $perPage,
            $orderByColumn,
            $order,
            $filters
        );

        return ApiResponse::page(
            $pageResult,
            AccountResource::class
        );
    }

    protected function mapClientFieldToColumn(string $field): string
    {
        if (!empty($this->columnsMap[$field])) {
            return $this->columnsMap[$field];
        } else {
            abort(400, "Invalid client field: $field.");
        }
    }

    protected function parseFilterField(?string $filterItems): array
    {
        $filters = [];

        if ($filterItems) {
            $filterItems = explode(",", $filterItems);

            $field = $filterItems[0];
            $value = $filterItems[1];

            $column = $this->mapClientFieldToColumn($field);

            $filters = [[$column, "=", $value]];
        }

        return $filters;
    }
}
