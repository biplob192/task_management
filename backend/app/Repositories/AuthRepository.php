<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthRepositoryInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

class AuthRepository implements AuthRepositoryInterface
{
    public function register($validatedData, $request)
    {
        try {
            return DB::transaction(function () use ($validatedData, $request) {
                // Create new user
                $user = User::create($validatedData);

                // Assign default role
                $user->assignRole('employee');

                // Craete user tokens for API
                if ($request->is('api/*')) {
                    $this->setUserToken($user);
                }

                return ['data' => $user, 'message' => "Registration successful! Welcome!", 'status' => 201];
            });
        } catch (Exception $e) {
            throw new Exception("Error registering user: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function login($request)
    {
        // Find the user and check the password
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new Exception("Invalid email or password.", 401);
        }

        // Check for user role
        if (! $user->roles->pluck('name')) {
            throw new Exception("User role not found.", 404);
        }

        // Craete user tokens for API
        if ($request->is('api/*')) {
            $this->setUserToken($user);
        } else {
            $credentials = $request->only('email', 'password');
            Auth::attempt($credentials);
        }

        return ['data' => $user, 'message' => 'Login successfully.', 'status' => 200];
    }


    public function logout($request)
    {
        if ($request->is('api/*')) {
            auth()->guard('api')->user()->tokens()?->delete();
        } else {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return ['data' => '', 'message' => 'Logged out successfully.', 'status' => 200];
    }


    public function refresh()
    {
        $user = auth()->guard('api')->user();

        // Revoke previous tokens and craete new one (both access and refresh token)
        $token = $this->createToken($user);
        $user->access_token = $token['access_token']->accessToken;
        $user->refresh_token = $token['refresh_token']->accessToken;

        return ['data' => $user, 'message' => 'Refresh token data.', 'status' => 200];
    }


    public function setUserToken($user)
    {
        try {
            $token = $this->createToken($user);
            $user->access_token = $token['access_token']->accessToken;
            $user->refresh_token = $token['refresh_token']->accessToken;

            return $user;
        } catch (Exception $e) {
            throw new Exception("Error setup user token: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }


    public function createToken($user)
    {
        try {
            // Revoke previous tokens
            $user->tokens()?->delete();
            // return $user->createToken('AccessToken')->accessToken;

            // Generate an access token with access scope that expires after 60*24 minutes
            $accessToken = $user->createToken('AccessToken', ['access']);
            $accessToken->token->expires_at = now()->addMinutes(60 * 24);
            $accessToken->token->save();

            // Generate another access token with refresh scope that expires after 60*24*7 minutes
            $refreshToken = $user->createToken('RefreshToken', ['refresh']);
            $refreshToken->token->expires_at = now()->addMinutes(60 * 24 * 7);
            $refreshToken->token->save();

            return ['access_token' => $accessToken, 'refresh_token' => $refreshToken];
        } catch (OAuthServerException $e) {
            Log::error('OAuth error: ' . $e->getMessage());
            throw new Exception("OAuth error occurred. " . $e->getMessage(), 400);
        } catch (Exception $e) {
            throw new Exception("Error creating token: " . $e->getMessage(), $e->getCode() ? $e->getCode() : 500);
        }
    }
}
