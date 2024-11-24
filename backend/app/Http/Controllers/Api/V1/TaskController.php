<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\TaskRepositoryInterface;
use App\Http\Controllers\Api\BaseController;

class TaskController extends BaseController
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private TaskService $taskService,
    ) {}


    public function index(Request $request)
    {
        try {
            $formatedData = $this->taskService->formateDataForIndexWithPaginate($request);
            $response = $this->taskRepository->indexWithPaginate($formatedData);

            return $this->sendResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function show($id)
    {
        try {
            $response = $this->taskRepository->show($id);
            return $this->sendResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function store(TaskRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Add the 'created_by' key with the current logged-in user's ID
            $validatedData['created_by'] = auth()->guard('api')->user()->id;

            // Start transaction wrapper
            $response = DB::transaction(function () use ($validatedData, $request) {
                // Create the task record
                $response = $this->taskRepository->store($validatedData, $request);

                // Collect task ID and assigned user IDs
                $taskId = $response['data']->id;
                $userIds = $request->input('assigned_users', []);

                // Assign users for the task
                $this->assignTaskUsers($taskId, $userIds);

                // Return response outside of the transaction function
                return $response;
            });

            return $this->sendResponse($response['data'], $response['message'], $response['status']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function update(TaskRequest $request, $id)
    {
        try {
            // Validate the request and get the validated data
            $validatedData = $request->validated();

            // Start transaction wrapper
            $response = DB::transaction(function () use ($validatedData, $request, $id) {
                // Update record
                $response =  $this->taskRepository->update($id, $validatedData, $request);

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
                $response = $this->taskRepository->delete($id);

                // Return response outside of the transaction function
                return $response;
            });

            return $this->sendResponse($response['data'], $response['message']);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }


    public function assignTaskUsers($taskId, $userIds)
    {
        foreach ($userIds as $userId) {
            DB::table('task_users')->insert([
                'task_id' => $taskId,
                'user_id' => $userId,
                // 'assigned_at' => now(), // Or other relevant fields
            ]);
        }
    }
}
