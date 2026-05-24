<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'lead_proponent_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'project_code' => 'nullable|string|unique:projects',
            'type' => 'required|in:research,extension',
            'status' => 'nullable|in:pending,ongoing,completed,cancelled',
            'funding_type' => 'required|in:internal,external,cofunded',
            'approved_budget' => 'required|numeric|min:0',
            'objectives' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_description' => 'nullable|string',
        ];
    }
}
