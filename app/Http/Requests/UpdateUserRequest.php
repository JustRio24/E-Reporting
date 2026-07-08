<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $routeParam = $this->route('user');

        // Route param may be a User model (route model binding) or an integer ID
        if ($routeParam instanceof User) {
            $userModel = $routeParam;
        } else {
            $userModel = User::find((int) $routeParam);
        }

        if (! $userModel) {
            return false;
        }

        return $this->user()->can('update', $userModel);
    }

    public function rules(): array
    {
        $routeParam = $this->route('user');
        $userId = $routeParam instanceof User ? $routeParam->id : (int) $routeParam;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', new Enum(UserRole::class)],
            'phone' => ['nullable', 'string', 'max:20'],
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
