<?php

namespace App\Repositories;

use App\Models\Label;

class LabelRepository
{
    public function getByUser($userId)
    {
        return Label::query()->where('user_id', $userId)->latest()->get();
    }

    public function create($userId, array $data)
    {
        return Label::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'bg_color' => $data['bg_color'],
        ]);
    }

    public function findOrFailOwned($id, $userId): Label
    {
        return Label::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function update(Label $label, array $data)
    {
        $label->update($data);
        return $label;
    }

    public function delete(Label $label)
    {
        return $label->delete();
    }
}
