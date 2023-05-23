<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $userData = $this->validateRegistrationRequest($request);

        $user = $this->createUser($userData);

        $token = $this->generateApiToken($user);

        return $this->createRegistrationResponse($user, $token);
    }

    /**
     * Validate the registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRegistrationRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user.
     *
     * @param  array  $userData
     * @return \App\Models\User
     */
    protected function createUser(array $userData)
    {
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);
    }

    /**
     * Generate an API token for the user.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    protected function generateApiToken($user)
    {
        return $user->createToken('api-token')->plainTextToken;
    }

    /**
     * Create the registration response.
     *
     * @param  \App\Models\User  $user
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createRegistrationResponse($user, $token)
    {
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
