<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $this->validateLoginRequest($request);

        if ($this->attemptLogin($credentials)) {
            $user = $this->getAuthenticatedUser();
            $token = $this->generateApiToken($user);

            return $this->createLoginResponse($user, $token);
        }

        throw $this->invalidLoginException();
    }

    /**
     * Validate the login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLoginRequest(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }

    /**
     * Attempt to authenticate the user.
     *
     * @param  array  $credentials
     * @return bool
     */
    protected function attemptLogin(array $credentials)
    {
        return Auth::attempt($credentials);
    }

    /**
     * Get the authenticated user.
     *
     * @return \App\Models\User
     */
    protected function getAuthenticatedUser()
    {
        return Auth::user();
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
     * Create the login response.
     *
     * @param  \App\Models\User  $user
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createLoginResponse($user, $token)
    {
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Get the invalid login exception.
     *
     * @return \Illuminate\Validation\ValidationException
     */
    protected function invalidLoginException()
    {
        return ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
