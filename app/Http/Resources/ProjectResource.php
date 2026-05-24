<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'project_code' => $this->project_code,
            'type' => $this->type,
            'status' => $this->status,
            'funding_type' => $this->funding_type,
            'approved_budget' => $this->approved_budget,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'location_description' => $this->location_description,
            'objectives' => $this->objectives,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'lead_proponent' => new UserResource($this->whenLoaded('leadProponent')),
            'budget' => new BudgetResource($this->whenLoaded('budget')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
