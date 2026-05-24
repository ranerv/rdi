<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IpApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'user_id' => $this->user_id,
            'application_type' => $this->application_type,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'reviewed_by' => $this->reviewed_by,
            'submitted_at' => $this->submitted_at,
            'reviewed_at' => $this->reviewed_at,
            'project' => new ProjectResource($this->whenLoaded('project')),
            'user' => new UserResource($this->whenLoaded('user')),
            'reviewer' => new UserResource($this->whenLoaded('reviewer')),
            'ip_certificate' => new IpCertificateResource($this->whenLoaded('ipCertificate')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
