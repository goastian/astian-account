<?php

namespace App\Http\Requests\Employee;

use App\Models\Auth;
use App\Models\User\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'name' => ['required','regex:/^[A-Za-z\s]+$/', 'max:100'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'country' => ['required', 'max:100', 'exists:countries,name_en'],
            'city' => ['nullable','regex:/^[A-Za-z\s]+$/', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'dial_code' => [Rule::requiredIf(request()->phone != null), 'max:8', 'exists:countries,dial_code'],
            'phone' => [Rule::requiredIf(request()->dial_code != null), 'max:25', 'unique:users,phone'],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before: ' . Employee::setBirthday()],
            'role' => ['required', 'array', 'exists:roles,id'],
        ];
    }
}
