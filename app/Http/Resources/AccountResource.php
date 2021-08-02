<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => $this->client->id,
            "name" => $this->client->client_name,
            "address1" => $this->client->address1,
            "address2" => $this->client->address2,
            "city" => $this->client->city,
            "state" => $this->client->state,
            "country" => $this->client->country,
            "zipCode" => $this->client->zip,
            "latitude" => $this->client->latitude,
            "longitude" => $this->client->longitude,
            "phoneNo1" => $this->client->phone_no1,
            "phoneNo2" => $this->client->phone_no2,
            "startValidity" => $this->client->start_validity,
            "endValidity" => $this->client->end_validity,
            "status" => $this->client->status,
        ];
    }
}
