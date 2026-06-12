<?php

namespace App\Http\Requests;

use App\Enums\DamageSeverity;
use App\Models\DamageReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDamageReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', DamageReport::class);
    }

    public function rules(): array
    {
        return [
            'facility_id' => ['required', 'exists:facilities,id'],
            'damage_category_id' => ['required', 'exists:damage_categories,id'],
            'severity' => ['required', new Enum(DamageSeverity::class)],
            'title' => ['required', 'string', 'max' => 255],
            'description' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photos' => ['required', 'array', 'min:1', 'max:5'],
            'photos.*' => ['image', 'max:4096'], // Enforces RULE-007 (Min 1 photo) and type check
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required' => 'Setidaknya satu foto kerusakan harus diunggah (RULE-007).',
            'photos.min' => 'Setidaknya satu foto kerusakan harus diunggah (RULE-007).',
            'photos.*.image' => 'File yang diunggah harus berupa gambar.',
            'photos.*.max' => 'Ukuran gambar maksimal adalah 4MB.',
        ];
    }
}
