<?php

namespace App\Http\Requests;

use App\Enums\DamageSeverity;
use App\Models\DamageReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateDamageReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $report = $this->route('damage_report');
        if (is_numeric($report)) {
            $report = DamageReport::find($report);
        }
        return $report && $this->user()->can('update', $report);
    }

    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'exists:facilities,id'],
            'damage_category_id' => ['required', 'exists:damage_categories,id'],
            'severity' => ['required', new Enum(DamageSeverity::class)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'max:4096'],
        ];
    }
}
