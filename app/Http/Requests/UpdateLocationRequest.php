<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role->value === 'admin';
    }

    public function rules(): array
    {
        $locationId = $this->route('location')?->id ?? $this->route('location');

        return [
            'name' => ['required', 'string', 'max' => 255, 'unique:locations,name,' . $locationId],
            'description' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
