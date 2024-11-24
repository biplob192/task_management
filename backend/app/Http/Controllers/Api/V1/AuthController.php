<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\AuthRepositoryInterface;
use App\Http\Controllers\Api\BaseController;

class AuthController extends BaseController
{
    public function __construct(private AuthRepositoryInterface $authRepository) {}


    public function register(RegisterRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $response = $this->authRepository->register($validatedData, $request);
            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', $e->getCode());
        }
    }


    public function login(LoginRequest $request)
    {
        try {
            $response = $this->authRepository->login($request);
            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', $e->getCode());
        }
    }


    public function logout(Request $request)
    {
        try {
            $response = $this->authRepository->logout($request);
            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', $e->getCode());
        }
    }


    public function refresh()
    {
        try {
            $response = $this->authRepository->refresh();
            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', $e->getCode());
        }
    }


    public function validateToken()
    {
        try {
            return $this->sendResponse('Token is valid.', 'Token is valid.', 200);
        } catch (Exception $e) {

            return $this->sendError($e->getMessage() ? $e->getMessage() : 'Internal server error.', $e->getCode());
        }
    }
}
