<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('email','test@test.com')->first();
        $user2 = User::query()->where('email','test2@test.com')->first();

        Label::create(['user_id' => $user->id, 'name' => 'Work', 'bg_color' => "rgb(158, 164, 243)"]);
        Label::create(['user_id' => $user->id, 'name' => 'Personal', 'bg_color' => "rgb(11 141 57)"]);
        Label::create(['user_id' => $user->id, 'name' => 'Shopping', 'bg_color' => "rgb(243, 158, 158)"]);

        Label::create(['user_id' => $user2->id, 'name' => 'Laravel', 'bg_color' => "rgb(158, 164, 243)"]);
        Label::create(['user_id' => $user2->id, 'name' => 'Vue', 'bg_color' => "rgb(11 141 57)"]);
        Label::create(['user_id' => $user2->id, 'name' => 'React', 'bg_color' => "rgb(243, 158, 158)"]);

    }
}
