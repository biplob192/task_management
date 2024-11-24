<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function register($validatedData, $request);
    public function login($request);
    public function logout($request);
    public function refresh();
}
