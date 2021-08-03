<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountTest extends TestCase
{
    public function test_account()
    {
        $response = $this->get('/api/v1/account');

        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "address1",
                    "address2",
                    "city",
                    "state",
                    "country",
                    "zipCode",
                    "latitude",
                    "longitude",
                    "phoneNo1",
                    "phoneNo2",
                    "startValidity",
                    "endValidity",
                    "status",
                ]
            ],
            "links" => [
                "path",
                "firstPageUrl",
                "lastPageUrl",
                "nextPageUrl",
                "prevPageUrl",
            ],
            "meta" => [
                "currentPage",
                "from",
                "lastPage",
                "perPage",
                "to",
                "total",
                "count",
            ]
        ]);

        $response->assertStatus(200);
    }
}
