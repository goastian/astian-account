<?php

namespace App\Http\Requests\Employee;

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
        return request()->user()->isAdmin() || request()->user()->id == Request('user')->id;
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
            'email' => ['required', 'email', 'max:100', 'unique:employees,email,' . Request('user')->id],
            'document_type' => ['required', 'max:30'],
            'document_number' => ['required', 'max:12', 'unique:employees,document_number,' . Request('user')->id],
            'country' => ['required', 'max:100'],
            'department' => ['required', 'max:100'],
            'address' => ['required', 'max:150'],
        ];
    }
}
