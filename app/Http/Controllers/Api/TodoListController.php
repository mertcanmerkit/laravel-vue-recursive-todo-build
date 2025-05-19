<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Services\TodoListService;
use Illuminate\Http\JsonResponse;


class TodoListController extends Controller
{
    public function __construct(protected TodoListService $service) {}

    public function index(): JsonResponse
    {
        $lists = $this->service->getUserLists();
        return response()->json(TodoListResource::collection($lists));
    }

    public function store(StoreTodoListRequest $request): JsonResponse
    {
        $list = $this->service->create($request->validated());
        return response()->json(new TodoListResource($list), 201);
    }

    public function show($id): JsonResponse
    {
        $list = $this->service->findUserList($id);
        return response()->json(new TodoListResource($list));
    }

    public function update(UpdateTodoListRequest $request, $id): JsonResponse
    {
        $list = $this->service->update($id, $request->validated());
        return response()->json(new TodoListResource($list));
    }

    public function destroy($id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}
