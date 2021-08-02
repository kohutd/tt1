<?php

namespace App\Services\Geocoding;

interface GeocodingService
{
    public function lookup(string $address): Coordinates;
}
