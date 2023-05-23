<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LogoutController extends Controller
{
    /**
     * Handle an incoming logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if (!$user) {
            throw $this->unauthenticatedException();
        }

        $this->revokeUserTokens($user);

        return $this->createLogoutResponse();
    }

    /**
     * Get the authenticated user.
     *
     * @return \App\Models\User|null
     */
    protected function getAuthenticatedUser()
    {
        return Auth::user();
    }

    /**
     * Revoke the user's API tokens.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function revokeUserTokens($user)
    {
        $user->tokens()->delete();
    }

    /**
     * Create the logout response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createLogoutResponse()
    {
        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Get the unauthenticated exception.
     *
     * @return \Illuminate\Validation\ValidationException
     */
    protected function unauthenticatedException()
    {
        return ValidationException::withMessages([
            'error' => __('auth.unauthenticated'),
        ])->status(401);
    }
}
