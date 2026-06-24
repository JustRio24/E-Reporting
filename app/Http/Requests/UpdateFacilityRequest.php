<?php

namespace App\Http\Requests;

use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $facilityId = $this->route('facility');
        $facility = Facility::find($facilityId);
        
        if (!$facility) {
            return false;
        }
        
        return $this->user()->can('update', $facility);
    }

    public function rules(): array
    {
        $facilityId = $this->route('facility')?->id ?? $this->route('facility');

        return [
            'facility_code' => ['required', 'string', 'max:50', 'unique:facilities,facility_code,' . $facilityId],
            'facility_name' => ['required', 'string', 'max:255'],
            'facility_category_id' => ['required', 'exists:facility_categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:10240'], // max 10MB (will be compressed to 2MB)
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
