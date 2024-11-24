<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\AuthRepository;

class GoogleService
{
    public function __construct(
        private AuthRepository $authRepository,
    ) {}

    public function redirectToGoogle($request)
    {
        // session()->flash('error', 'configuration is missing!');
        try {
            $clientID               = env('GOOGLE_CLIENT_ID');
            $clientSecret           = env('GOOGLE_CLIENT_SECRET');
            $redirectUri            = env('GOOGLE_REDIRECT_URI');
            $frontendRedirectUri    = env('FRONTEND_GOOGLE_REDIRECT_URI');

            if (!$clientID || !$clientSecret || !$redirectUri || !$frontendRedirectUri) {
                throw new Exception("Google OAuth configuration is missing!", 404);
            }

            if ($request->is('api/*')) {
                return response()->json([
                    'url' => Socialite::driver('google')
                        ->stateless() // Ensure stateless for API
                        ->redirectUrl($frontendRedirectUri)
                        ->redirect()
                        ->getTargetUrl(),
                ]);
            }

            // For web, use default redirect
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function handleGoogleCallback($request = null)
    {
        try {
            // Use stateless to avoid session issues in API
            $frontendRedirectUri = env('FRONTEND_GOOGLE_REDIRECT_URI');
            $socialiteDriver = $request?->is('api/*')
                ? Socialite::driver('google')->stateless()->redirectUrl($frontendRedirectUri) // For API
                : Socialite::driver('google'); // For web

            $userData = $socialiteDriver->user();

            // $userData = Socialite::driver('google')->user();
            $user = User::where('google_id', $userData->id)->first();

            if (!$user) {
                $user = User::updateOrCreate(
                    ['email' => $userData->email],
                    [
                        'name' => $request?->is('api/*') ? $userData->getName() : $userData->name,
                        'google_id' => $request?->is('api/*') ? $userData->getId() : $userData->id,
                        'password' => encrypt('password')
                    ]
                );

                // Assign role for the user
                $user->assignRole('User');
            }

            // Check for user role
            if (! $user->roles->pluck('name')) {
                throw new Exception("User role not found.", 404);
            }

            // Craete user tokens for API
            if ($request?->is('api/*')) {
                $user = $this->authRepository->setUserToken($user);
            } else {
                Auth::login($user);
            }

            return ['data' => $user, 'message' => 'Login successfully.', 'status' => 200];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
