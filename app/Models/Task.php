<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['todo_list_id', 'parent_id', 'label_id', 'name', 'priority', 'is_completed'];

    public function list() {
        return $this->belongsTo(TodoList::class, 'todo_list_id');
    }

    public function parent() {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(Task::class, 'parent_id')->with('children');
    }


    public function labels() {
        return $this->belongsToMany(Label::class);
    }
}
