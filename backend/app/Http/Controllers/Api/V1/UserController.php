<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Api\BaseController;

class UserController extends BaseController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserService $userService,
    ) {}


    public function index(Request $request)
    {
        try {
            $formatedData = $this->userService->formateDataForIndexWithPaginate($request);
            $response = $this->userRepository->indexWithPaginate($formatedData);

            return $this->sendResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function show($id)
    {
        try {
            $response = $this->userRepository->show($id);
            return $this->sendResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function store(UserRequest $request)
    {
        try {
            // Validate the request and get the validated data
            $validatedData = $request->validated();

            // Start transaction wrapper
            $response = DB::transaction(function () use ($validatedData, $request) {
                // Store basic user data
                $response = $this->userRepository->store($validatedData, $request);

                // Special updates like: profile_image, assign_role etc.
                updateUser($response['data'], $validatedData, $request);

                // Return response outside of the transaction function
                return $response;
            });

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function update(UserRequest $request, $id)
    {
        try {
            // Validate the request and get the validated data
            $validatedData = $request->validated();

            // Start transaction wrapper
            $response = DB::transaction(function () use ($validatedData, $request, $id) {
                // Remove the 'profile_image' from validated data for generic update
                unset($validatedData['profile_image']);

                // Update record
                $response = $this->userRepository->update($id, $validatedData, $request);

                // Special updates like: profile_image, assign_role etc.
                updateUser($response['data'], $validatedData, $request);

                // Return response outside of the transaction function
                return $response;
            });

            return $this->sendResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function destroy($id)
    {
        try {
            // Start transaction wrapper
            $response = DB::transaction(function () use ($id) {
                $response = $this->userRepository->delete($id);

                // Return response outside of the transaction function
                return $response;
            });

            return $this->sendResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }
}
