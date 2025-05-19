<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_tasks' => $this->total_tasks ?? 0,
            'completed_tasks' => $this->completed_tasks ?? 0,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
