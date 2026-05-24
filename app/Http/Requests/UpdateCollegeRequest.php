<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollegeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|unique:colleges,name,' . $this->college->id,
            'code' => 'nullable|string|unique:colleges,code,' . $this->college->id,
        ];
    }
}
