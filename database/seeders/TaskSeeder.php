<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list1 = TodoList::query()->where('name', 'Work Projects')->first();
        $list2 = TodoList::query()->where('name', 'Shopping List')->first();
        $list3 = TodoList::query()->where('name', 'My Todo')->first();

        $labelWork = Label::query()->where('name', 'Work')->first();
        $labelPersonal = Label::query()->where('name', 'Personal')->first();
        $labelShopping = Label::query()->where('name', 'Shopping')->first();

        $labelLaravel = Label::query()->where('name', 'Laravel')->first();
        $labelVue = Label::query()->where('name', 'Vue')->first();
        $labelReact = Label::query()->where('name', 'React')->first();

        $task1 = Task::create([
            'todo_list_id' => $list1->id,
            'name' => 'Setup project repo',
            'priority' => 'high',
            'is_completed' => false,
        ]);
        $task1->labels()->attach([$labelWork->id, $labelPersonal->id, $labelShopping]);

        $subTask1 = Task::create([
            'todo_list_id' => $list1->id,
            'parent_id' => $task1->id,
            'name' => 'Write README.md',
            'priority' => 'medium',
            'is_completed' => false,
        ]);
        $subTask1->labels()->attach([$labelPersonal->id]);

        $subSubTask1 = Task::create([
            'todo_list_id' => $list1->id,
            'parent_id' => $subTask1->id,
            'name' => 'Explain project goals',
            'priority' => 'low',
            'is_completed' => true,
        ]);
        $subSubTask1->labels()->attach([$labelWork->id, $labelPersonal->id]);

        $subSubTask2 = Task::create([
            'todo_list_id' => $list1->id,
            'parent_id' => $subTask1->id,
            'name' => 'Add installation steps',
            'priority' => 'low',
            'is_completed' => true,
        ]);
        $subSubTask2->labels()->attach([$labelWork->id]);

        $subSubSubTask = Task::create([
            'todo_list_id' => $list1->id,
            'parent_id' => $subSubTask2->id,
            'name' => 'List required software',
            'priority' => 'medium',
            'is_completed' => true,
        ]);
        $subSubSubTask->labels()->attach([$labelPersonal->id]);

        $task2 = Task::create([
            'todo_list_id' => $list2->id,
            'name' => 'Buy milk',
            'priority' => 'low',
            'is_completed' => false,
        ]);
        $task2->labels()->attach([$labelPersonal->id]);

        $list3->tasks()->create([
            'name' => 'Learn Laravel',
            'priority' => 'high',
            'is_completed' => false,
        ])->labels()->attach([$labelLaravel->id]);
    }
}
