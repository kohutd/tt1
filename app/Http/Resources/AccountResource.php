<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->client_name,
            "address1" => $this->address1,
            "address2" => $this->address2,
            "city" => $this->city,
            "state" => $this->state,
            "country" => $this->country,
            "zipCode" => $this->zip,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "phoneNo1" => $this->phone_no1,
            "phoneNo2" => $this->phone_no2,
            "startValidity" => $this->start_validity,
            "endValidity" => $this->end_validity,
            "status" => $this->status,
        ];
    }
}
