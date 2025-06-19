<?php

namespace App\Http\Requests\User;

use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:60'],
            'birthday' => ['required', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
            'accept_terms' => ['required', 'boolean'],
            'accept_cookies' => ['nullable', 'boolean'],
            'referral_code' => ['nullable'],
        ];
    }
}
