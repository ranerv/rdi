<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIpApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'application_type' => 'required|string',
            'status' => 'nullable|in:pending,under_review,for_revisions,approved,rejected',
            'remarks' => 'nullable|string',
        ];
    }
}
