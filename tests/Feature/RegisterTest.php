<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register()
    {
        $rand = rand();

        $email = "davidle-{$rand}@gmail.com";

        $response = $this->json(
            "POST",
            "/api/v1/register",
            [
                "name" => "XXX",
                "address1" => "Tsekhova, 1",
                "address2" => "",
                "city" => "Lviv",
                "state" => "Lviv oblast",
                "country" => "Ukraine",
                "zipCode" => "42321",
                "phoneNo1" => "38040294023",
                "phoneNo2" => "38040294023",
                "user" => [
                    "firstName" => "David",
                    "lastName" => "Le",
                    "email" => $email,
                    "password" => "password",
                    "passwordConfirmation" => "password",
                    "phone" => "38068924984",
                ]
            ]
        );

        $response->assertStatus(200);

        $response->assertJsonStructure([
            "data" => [
                "token",
                "user" => [
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

        $user = $response->json("data")["user"];

        $this->assertEquals("XXX", $user["name"]);
        $this->assertEquals("Tsekhova, 1", $user["address1"]);
        $this->assertEquals(null, $user["address2"]);
        $this->assertEquals("Lviv", $user["city"]);
        $this->assertEquals("Lviv oblast", $user["state"]);
        $this->assertEquals("Ukraine", $user["country"]);
        $this->assertEquals("42321", $user["zipCode"]);
        $this->assertEquals("38040294023", $user["phoneNo1"]);
        $this->assertEquals("38040294023", $user["phoneNo2"]);
        $this->assertEquals("Active", $user["status"]);

        $this->assertDatabaseHas("users", [
            "email" => $email,
        ]);
    }
}
