<?php

namespace App\Services\Geocoding;

class Coordinates
{
    public string $type;

    public float $latitude;

    public float $longitude;

    public function __construct(string $type, float $latitude, float $longitude)
    {
        $this->type = $type;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
