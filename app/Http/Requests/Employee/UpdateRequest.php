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
        return request()->user()->tokenCan('admin') || request()->user()->id == Request('user')->id;
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
            'document_type' => ['nullable', Rule::in(EnumType::documento_type())],
            'document_number' => ['nullable', 'max:12', 'unique:employees,document_number,' . Request('user')->id],
            'country' => ['nullable', 'max:100'],
            'department' => ['nullable', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'phone' => ['nullable', 'max:9', 'unique:employees,phone,' . request()->user->id]
        ];
    }
}
