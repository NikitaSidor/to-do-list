<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'done' => $this->done,
            'checkbox' => [
                'class' => "task__id",
                'name' => 'table__cell-id',
                'key' => $this->id  // Assuming 'id' is a property of your Task model
            ],
            'title' => [
                'class' => 'table__cell--title table__cell--no-wrap task__title',
                'value' => $this->title  // Assuming 'title' is a property of your Task model
            ],
            'description' => [
                'class' => 'table__cell--remainder task__description',
                'value' => $this->description  // Assuming 'description' is a property of your Task model
            ],
            'date' => [
                'class' => 'table__cell--remainder task__date',
                'value' => $this->date ? Carbon::parse($this->date)->format('d.m.Y') : null
            ],
        ];
    }
}
