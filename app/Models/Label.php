<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = ['user_id', 'name', 'bg_color'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tasks() {
        return $this->belongsToMany(Task::class);
    }
}
