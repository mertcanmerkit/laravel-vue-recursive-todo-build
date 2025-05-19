<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function __construct(protected TaskRepository $repo) {}

    public function getListTasks($listId)
    {
        return $this->repo->getByList($listId, Auth::id());
    }

    public function create($listId, array $data)
    {
        return $this->repo->create($listId, Auth::id(), $data);
    }

    public function findListTask($listId, $taskId)
    {
        return $this->repo->findOrFail($listId, $taskId, Auth::id());
    }

    public function update($listId, $taskId, array $data)
    {
        $task = $this->repo->findOrFail($listId, $taskId, Auth::id());
        return $this->repo->update($task, $data);
    }

    public function delete($listId, $taskId)
    {
        $task = $this->repo->findOrFail($listId, $taskId, Auth::id());
        return $this->repo->delete($task);
    }
}
