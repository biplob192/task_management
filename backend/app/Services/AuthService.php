<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    // public function register($validatedData)
    // {
    //     try {
    //         return DB::transaction(function () use ($validatedData) {
    //             $user = User::create([
    //                 'name' => $validatedData['name'],
    //                 'email' => $validatedData['email'],
    //                 'phone' => $validatedData['phone'],
    //                 'password' => Hash::make($validatedData['password']),
    //             ]);

    //             // Assign default role
    //             $user->assignRole('employee');

    //             return $user;
    //         });
    //     } catch (Exception $e) {
    //         throw new Exception("User registration failed! " . $e->getMessage());
    //     }
    // }


    // public function logout($request)
    // {
    //     try {
    //         Auth::logout();
    //         $request->session()->invalidate();
    //         $request->session()->regenerateToken();

    //         return true;
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }
    // }
}
