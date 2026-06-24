<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacilityCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role->value === 'admin';
    }

    public function rules(): array
    {
        $categoryId = $this->route('facility_category')?->id ?? $this->route('facility_category');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:facility_categories,name,' . $categoryId],
            'description' => ['nullable', 'string'],
        ];
    }
}
