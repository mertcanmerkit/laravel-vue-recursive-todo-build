<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}

    public function index(Request $request, $listId): JsonResponse
    {
        $tasks = $this->service->getListTasks($listId);
        return response()->json(TaskResource::collection($tasks));
    }

    public function store(StoreTaskRequest $request, $listId): JsonResponse
    {
        $task = $this->service->create($listId, $request->validated());
        return response()->json(new TaskResource($task), 201);
    }

    public function show($listId, $taskId): JsonResponse
    {
        $task = $this->service->findListTask($listId, $taskId);
        return response()->json(new TaskResource($task));
    }

    public function update(UpdateTaskRequest $request, $listId, $taskId): JsonResponse
    {
        $task = $this->service->update($listId, $taskId, $request->validated());
        return response()->json(new TaskResource($task));
    }

    public function destroy($listId, $taskId): JsonResponse
    {
        $this->service->delete($listId, $taskId);
        return response()->json(null, 204);
    }
}
