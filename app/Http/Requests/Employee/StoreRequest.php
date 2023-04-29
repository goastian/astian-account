<?php

namespace App\Http\Requests\Employee;

use App\Models\Auth;
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
            'document_type' => ['required', 'max:30'],
            'document_number' => ['required', 'max:12', 'unique:employees,document_number'],
            'country' => ['required', 'max:100'],
            'department' => ['required', 'max:100'],
            'address' => ['required', 'max:150'],
            'role' => ['required', 'array','exists:roles,id']
        ];
    }
}
