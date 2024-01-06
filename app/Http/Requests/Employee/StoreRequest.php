<?php

namespace App\Http\Requests\Employee;

use App\Models\Auth;
use App\Enum\EnumType;
use App\Models\User\Employee;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'name' => ['required', 'max:100'],
            'last_name' => ['required', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:employees,email'],
            'country' => ['required', 'max:100'],
            'city' => ['required', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'phone' => ['required', 'max:20', 'unique:employees,phone'],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before: ' . Employee::setBirthday()],
            'role' => ['required', 'array','exists:roles,id'],
        ];
    }
}
