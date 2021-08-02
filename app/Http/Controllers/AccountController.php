<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Http\Resources\AccountResource;
use App\Repositories\UsersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected UsersRepository $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function getAll(Request $request): JsonResponse
    {
        $page = $request->get("page", 1);
        $perPage = $request->get("perPage", 10);
        $order = $request->get("order", "desc");
        $orderBy = $request->get("orderBy", "id");

        $filter = $request->get("filter");

        $filters = [];

        if ($filter) {
            $filter = explode(",", $filter);

            $filters = [[$filter[0], "=", $filter[1]]];
        }

        $pageResult = $this->usersRepository->getPage(
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
