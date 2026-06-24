<?php

namespace App\Http\Requests;

use App\Models\FacilityCategory;
use Illuminate\Foundation\Http\FormRequest;

class StoreFacilityCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role->value === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:facility_categories,name'],
            'description' => ['nullable', 'string'],
        ];
    }
}
