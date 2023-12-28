<?php

namespace App\Http\Requests\Employee;

use App\Enum\EnumType; 
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => ['nullable', 'max:100'],
            'last_name' => ['nullable', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:employees,email,' . Request('user')->id],
            'country' => ['nullable', 'max:100'],
            'city' => ['nullable', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'birthday' => ['nullable', 'date_format:Y-m-d'],
            'phone' => ['nullable', 'max:9', 'unique:employees,phone,' . request()->user->id]
        ];
    }
}
