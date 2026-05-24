<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'total_amount' => 'required|numeric|min:0',
            'allocated' => 'nullable|numeric|min:0',
            'released' => 'nullable|numeric|min:0',
        ];
    }
}
