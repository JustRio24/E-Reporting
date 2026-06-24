<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDamageCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role->value === 'admin';
    }

    public function rules(): array
    {
        $categoryId = $this->route('damage_category')?->id ?? $this->route('damage_category');

        return [
            'name' => ['required', 'string', 'max:255', 'unique:damage_categories,name,' . $categoryId],
            'description' => ['nullable', 'string'],
        ];
    }
}
