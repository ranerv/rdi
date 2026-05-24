<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitoringReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'officer_id' => $this->officer_id,
            'quarter' => $this->quarter,
            'year' => $this->year,
            'accomplishments' => $this->accomplishments,
            'workplan_status' => $this->workplan_status,
            'status' => $this->status,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'officer' => new UserResource($this->whenLoaded('officer')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
