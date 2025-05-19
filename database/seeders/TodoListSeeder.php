<?php

namespace Database\Seeders;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('email','test@test.com')->first();
        $user2 = User::query()->where('email','test2@test.com')->first();

        TodoList::create(['user_id' => $user->id, 'name' => 'Work Projects']);
        TodoList::create(['user_id' => $user->id, 'name' => 'Shopping List']);

        TodoList::create(['user_id' => $user2->id, 'name' => 'My Todo']);
    }
}
