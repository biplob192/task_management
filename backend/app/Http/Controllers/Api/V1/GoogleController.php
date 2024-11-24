<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\GoogleService;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\BaseController;

class GoogleController  extends BaseController
{
    public function __construct(private GoogleService $googleService) {}


    public function redirectToGoogle(Request $request)
    {
        try {
            return $this->googleService->redirectToGoogle($request);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', $e->getCode());
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $response = $this->googleService->handleGoogleCallback($request);
            return $this->sendResponse($response['data'], $response['message'], $response['status']);



            // Use stateless to avoid session issues in API
            $redirectUri = env('FRONTEND_GOOGLE_REDIRECT_URI');
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->redirectUrl($redirectUri)
                ->user();

            // Example: Return token or save user to database
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => encrypt('password')
                ]
            );

            // Assign role for the user
            $user->assignRole('User');
            $user->roles->pluck('name');

            // $token = $user->createToken('authToken')->plainTextToken;

            // Generate an access token with access scope that expires after 60*24 minutes
            $accessToken = $user->createToken('AccessToken', ['access']);
            $accessToken->token->expires_at = now()->addMinutes(60 * 24);
            $accessToken->token->save();

            // Generate another access token with refresh scope that expires after 60*24*7 minutes
            $refreshToken = $user->createToken('RefreshToken', ['refresh']);
            $refreshToken->token->expires_at = now()->addMinutes(60 * 24 * 7);
            $refreshToken->token->save();

            $user->access_token = $accessToken->accessToken;
            $user->refresh_token = $refreshToken->accessToken;

            return response()->json([
                'status' => 200,
                'message' => 'Authenticated successfully',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', $e->getCode());
            // return response()->json([
            //     'status' => 500,
            //     'message' => 'Authentication failed',
            //     'error' => $e->getMessage(),
            // ]);
        }
    }
}
