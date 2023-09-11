<?php

namespace App\Http\Requests\Room;

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
            'number' => ['required','max:5', 'unique:rooms,number'],
            'capacity' => ['required','integer', 'max:20'],
            'description' => ['nullable', 'max:150'],
            'note' => ['nullable', 'max:150'],
            'category_id' => ['required','exists:categories,id']
        ];
    }
}
