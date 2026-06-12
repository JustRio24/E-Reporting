<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;

class StoreFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Facility::class);
    }

    public function rules(): array
    {
        return [
            'facility_code' => ['required', 'string', 'max' => 50, 'unique:facilities,facility_code'],
            'facility_name' => ['required', 'string', 'max' => 255],
            'facility_category_id' => ['required', 'exists:facility_categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'description' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
