<?php

namespace App\Repositories;

use App\Models\Label;
use App\Models\Task;

class TaskRepository
{
    public function getByList($listId, $userId)
    {
        return Task::query()
            ->with(['children', 'labels'])
            ->where('todo_list_id', $listId)
            ->whereHas('list', fn ($q) => $q->where('user_id', $userId))
            ->whereNull('parent_id')
            ->get();
    }

    public function create(int $listId, int $userId, array $data)
    {
        $task = Task::create([
            'todo_list_id' => $listId,
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'priority' => $data['priority'],
            'is_completed' => $data['is_completed'] ?? false,
        ]);

        if (!empty($data['label_names'])) {
            $labelIds = [];

            foreach ($data['label_names'] as $label) {
                $label = Label::firstOrCreate(
                    ['user_id' => $userId, 'name' => $label['name']],
                    ['bg_color' => $label['bg_color']]
                );
                $labelIds[] = $label->id;
            }

            $task->labels()->syncWithoutDetaching($labelIds);
        }

        return $task->load(['labels', 'children']);
    }


    public function findOrFail($listId, $taskId, $userId): Task
    {
        return Task::query()
            ->where('id', $taskId)
            ->where('todo_list_id', $listId)
            ->whereHas('list', fn ($q) => $q->where('user_id', $userId))
            ->with(['labels', 'children'])
            ->firstOrFail();
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);

        if (isset($data['label_names'])) {
            $labelIds = [];

            foreach ($data['label_names'] as $label) {
                $label = Label::firstOrCreate(
                    ['user_id' => $task->list->user_id, 'name' => $label['name']],
                    ['bg_color' => $label['bg_color']]
                );
                $labelIds[] = $label->id;
            }

            $task->labels()->sync($labelIds);
        }


        return $task->load(['labels', 'children']);
    }

    public function delete(Task $task)
    {
        return $task->delete();
    }
}
