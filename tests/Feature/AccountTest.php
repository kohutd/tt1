<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountTest extends TestCase
{
    public function test_account()
    {
        $perPage = 10;

        $response = $this->get('/api/v1/account', [
            'page' => 1,
            'perPage' => $perPage,
        ]);

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
            ]
        ]);

        $data = $response->json("data");

        $this->assertLessThanOrEqual(sizeof($data), $perPage);

        $response->assertStatus(200);
    }
}
