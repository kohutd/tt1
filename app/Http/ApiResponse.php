<?php

namespace App\Http;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ApiResponse
{
    public static function success($data): JsonResponse
    {
        return Response::json([
            'data' => $data,
        ]);
    }

    public static function page(LengthAwarePaginator $paginator, ?string $resourceForMapping = null): JsonResponse
    {
        return Response::json([
            'data' => $resourceForMapping ? $resourceForMapping::collection($paginator->items()) : $resourceForMapping,
            'links' => [
                'path' => $paginator->path(),
                'firstPageUrl' => $paginator->url(1), // todo: ?
                'lastPageUrl' => $paginator->url($paginator->lastPage()),
                'nextPageUrl' => $paginator->nextPageUrl(),
                'prevPageUrl' => $paginator->previousPageUrl(),
            ],
            'meta' => [
                'currentPage' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
                'count' => sizeof($paginator->items())
            ]
        ]);
    }
}
