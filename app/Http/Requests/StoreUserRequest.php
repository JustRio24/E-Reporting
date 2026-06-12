<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\User::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max' => 255],
            'email' => ['required', 'string', 'email', 'max' => 255, 'unique:users,email'],
            'password' => ['required', 'string', 'min' => 8, 'confirmed'],
            'role' => ['required', new Enum(UserRole::class)],
            'phone' => ['nullable', 'string', 'max' => 20],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
}
