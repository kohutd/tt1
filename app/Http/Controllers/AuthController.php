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
            'name' => 'required|max:100',
            'address1' => 'required',
            'address2' => 'nullable',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'country' => 'required|max:100',
            'zipCode' => 'required|max:20',
            'phoneNo1' => 'required|max:20',
            'phoneNo2' => 'required|max:20',
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
            'firstName' => 'required|max:50',
            'lastName' => 'required|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:256',
            'passwordConfirmation' => 'required|max:256|same:password',
            'phone' => 'required|max:20',
        ])->validate();

        $client = $this->clientRepository->create($validatedClient);

        $user = $this->userRepository->create($validatedUser, $client);

        return ApiResponse::success([
            "user" => AccountResource::make($user),
            "token" => $user->createToken('register-token')->plainTextToken
        ]);
    }
}
