<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'priority' => $this->priority,
            'is_completed' => $this->is_completed,
            'todo_list_id' => $this->todo_list_id,
            'labels' => $this->labels->map(fn($label) => [
                'name' => $label->name,
                'bg_color' => $label->bg_color
            ]),
            'children' => TaskResource::collection($this->whenLoaded('children')),
        ];
    }
}
