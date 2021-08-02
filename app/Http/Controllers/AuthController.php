<?php

namespace App\Http\Controllers;

use App\Http\ApiResponse;
use App\Http\Resources\AccountResource;
use App\Repositories\ClientsRepository;
use App\Repositories\UsersRepository;
use App\Services\Geocoding\GeocodingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    protected GeocodingService $geocodingService;

    protected ClientsRepository $clientRepository;
    protected UsersRepository $userRepository;

    public function __construct(ClientsRepository $clientRepository, UsersRepository $userRepository, GeocodingService $geocodingService)
    {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;

        $this->geocodingService = $geocodingService;
    }

    public function register(Request $request): JsonResponse
    {
        $validatedClient = $request->validate([
            'name' => 'required',
            'address1' => 'required',
            'address2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zipCode' => 'required',
            'phoneNo1' => 'required',
            'phoneNo2' => 'required',
            'user' => 'required|array'
        ]);

        $geoInfo = $this->geocodingService->lookup(
        // address, city, state, country
            $validatedClient['address1'] . ', ' . $validatedClient['city'] . ', ' . $validatedClient['state'] . ', ' . $validatedClient['country']
        );

        $validatedClient['latitude'] = $geoInfo->latitude;
        $validatedClient['longitude'] = $geoInfo->longitude;

        $validatedClient['status'] = 'Active';
        $validatedClient['start_validity'] = Carbon::now();
        $validatedClient['end_validity'] = Carbon::now()->addDays(15);

        $validatedUser = Validator::make($validatedClient['user'], [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'passwordConfirmation' => 'required|same:password',
            'phone' => 'required',
        ])->validate();

        $client = $this->clientRepository->create($validatedClient);

        $user = $this->userRepository->create($validatedUser, $client);

        return ApiResponse::success([
            "user" => AccountResource::make($user),
            "token" => $user->createToken('register-token')->plainTextToken
        ]);
    }
}
