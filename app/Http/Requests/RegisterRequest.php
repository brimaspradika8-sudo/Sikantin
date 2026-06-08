<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,seller'],
            'store_name' => ['required_if:role,seller', 'string', 'max:255'],
            'phone' => ['required_if:role,seller', 'string', 'max:25'],
            'address' => ['required_if:role,seller', 'string', 'max:500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => strip_tags(trim($this->input('name'))),
            'email' => strip_tags(trim($this->input('email'))),
            'role' => strip_tags(trim($this->input('role', 'user'))),
            'store_name' => strip_tags(trim($this->input('store_name'))),
            'phone' => strip_tags(trim($this->input('phone'))),
            'address' => strip_tags(trim($this->input('address'))),
        ]);
    }
}
