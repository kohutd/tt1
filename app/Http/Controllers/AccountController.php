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
        if (!empty($this->columnsMap[$orderBy])) {
            $orderBy = $this->columnsMap[$orderBy];
        } else {
            abort(400, "Invalid column to order.");
        }

        $filter = $request->get("filter");

        $filters = [];

        if ($filter) {
            $filter = explode(",", $filter);

            $column = $filter[0];
            if (!empty($this->columnsMap[$column])) {
                $column = $this->columnsMap[$column];
            } else {
                abort(400, "Invalid column to filter.");
            }

            $value = $filter[1];

            $filters = [[$column, "=", $value]];
        }

        $pageResult = $this->clientsRepository->getPage(
            $page,
            $perPage,
            $orderBy,
            $order,
            $filters
        );

        return ApiResponse::page(
            $pageResult,
            AccountResource::class
        );
    }
}
