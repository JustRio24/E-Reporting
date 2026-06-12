<?php

namespace App\Http\Requests;

use App\Models\RepairProgress;
use Illuminate\Foundation\Http\FormRequest;

class StoreRepairProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', RepairProgress::class);
    }

    public function rules(): array
    {
        return [
            'progress_percentage' => ['required', 'integer', 'between:0,100'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
