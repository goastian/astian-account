<?php

namespace App\Http\Requests\Room;

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
            'number' => ['nullable','max:5', 'unique:rooms,number,'. Request('room')->id],
            'description' => ['nullable', 'max:150'],
            'note' => ['nullable', 'max:150'],
            'category_id' => ['nullable','exists:categories,id']
        ];
    }
}
