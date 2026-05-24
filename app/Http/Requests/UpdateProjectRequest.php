<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department_id' => 'nullable|exists:departments,id',
            'lead_proponent_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string|max:255',
            'project_code' => 'nullable|string|unique:projects,project_code,' . $this->project->id,
            'type' => 'nullable|in:research,extension',
            'status' => 'nullable|in:pending,ongoing,completed,cancelled',
            'funding_type' => 'nullable|in:internal,external,cofunded',
            'approved_budget' => 'nullable|numeric|min:0',
            'objectives' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_description' => 'nullable|string',
        ];
    }
}
