<?php

namespace App\Services\Geocoding;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleGeocodingService implements GeocodingService
{
    public function call(string $address): Response
    {
        return Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            "address" => $address,
            "key" => config("geocoding.google_token")
        ])->throw();
    }

    public function lookup(string $address): Coordinates
    {
        $address = str_replace(" ", "+", $address);
        $cacheKey = "address:" . md5($address);

        if ($coordinates = Cache::get($cacheKey)) {
            return $coordinates;
        }

        $response = $this->call($address);

        $defaultCoordinates = new Coordinates("geo", 0, 0);

        $first = $response->json("results")[0] ?? null;
        if (!$first) {
            return $defaultCoordinates;
        }

        $geometry = $first["geometry"] ?? null;
        if (!$geometry) {
            return $defaultCoordinates;
        }

        $location = $geometry["location"] ?? null;
        if (!$location) {
            return $defaultCoordinates;
        }

        $coordinates = new Coordinates($geometry["location_type"], $location["lat"], $location["lng"]);

        Cache::put($cacheKey, $coordinates);

        return $coordinates;
    }
}
