<?php

namespace App\Repositories;

use App\Models\TodoList;
use Illuminate\Database\Eloquent\Builder;

class TodoListRepository
{
    public function getByUser($userId)
    {
        return TodoList::query()
            ->where('user_id', $userId)
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => function (Builder $query) {
                    $query->where('is_completed', true);
                },
            ])
            ->latest()
            ->get();
    }

    public function create($userId, array $data)
    {
        return TodoList::create([
            'user_id' => $userId,
            'name' => $data['name'],
        ]);
    }

    public function findOrFailOwned($id, $userId): TodoList
    {
        return TodoList::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function update(TodoList $list, array $data)
    {
        $list->update($data);
        return $list;
    }

    public function delete(TodoList $list)
    {
        return $list->delete();
    }
}
