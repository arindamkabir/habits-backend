<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HabitResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'description' => $this->when(
                $request->routeIs('habits.show'),
                $this->description
            ),
            'category' => $this->whenLoaded('category', function () {
                return new HabitCategoryResource($this->category);
            }),
            'entries' => $this->whenLoaded('entries', function () {
                return HabitEntryResource::collection($this->entries);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
