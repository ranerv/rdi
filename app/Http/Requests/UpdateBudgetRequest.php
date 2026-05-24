<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'total_amount' => 'nullable|numeric|min:0',
            'allocated' => 'nullable|numeric|min:0',
            'released' => 'nullable|numeric|min:0',
        ];
    }
}
