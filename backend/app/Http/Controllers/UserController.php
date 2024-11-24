<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\UserRepositoryInterface;

class UserController extends Controller
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

            return view('users.index', [
                'users' => $response,
                'perPage' => $formatedData->perPage,
                'search' => $formatedData->search,
                'specialSearch' => $formatedData->specialSearch,
                'customConditions' => $formatedData->customConditions,
            ]);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Unable to show the users. ' . $e->getMessage()]);
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

            return back()->with('success', $response['message']);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Unable to store the user. ' . $e->getMessage()]);
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

            return back()->with('success', $response['message']);
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Unable to update the user. ' . $e->getMessage()]);
        }
    }


    public function ajaxShow($id)
    {
        return $this->userService->ajaxShow($id);
    }


    public function ajaxDestroy($id)
    {
        return $this->userService->ajaxDestroy($id);
    }
}
