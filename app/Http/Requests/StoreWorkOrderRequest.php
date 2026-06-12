<?php

namespace App\Http\Requests;

use App\Models\WorkOrder;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', WorkOrder::class);
    }

    public function rules(): array
    {
        return [
            'assigned_to' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = User::find($value);
                    if ($user && $user->role !== UserRole::MAINTENANCE) {
                        $fail('Petugas yang dipilih harus memiliki peran Maintenance Team.');
                    }
                    if ($user && !$user->is_active) {
                        $fail('Petugas yang dipilih saat ini tidak aktif.');
                    }
                }
            ],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
