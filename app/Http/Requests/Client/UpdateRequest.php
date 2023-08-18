<?php

namespace App\Http\Requests\Client;

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
        return request()->user()->canWrite() || request()->user()->isAdmin();
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
            'document' => ['nullable', 'max:100'],
            'number' => ['nullable', 'max:50', 'unique:clients,number,' . Request('client')->id],
            'city' => ['nullable', 'max:100'],
            'country' => ['nullable', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => ['nullable', 'max:9'],
        ];
    }
}
