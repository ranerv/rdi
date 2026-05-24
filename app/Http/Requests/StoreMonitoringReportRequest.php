<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonitoringReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'quarter' => 'required|integer|between:1,4',
            'year' => 'required|integer|min:2020',
            'accomplishments' => 'required|string',
            'workplan_status' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,reviewed',
        ];
    }
}
