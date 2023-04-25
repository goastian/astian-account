<?php

namespace App\Http\Requests\CategoryCalendar;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Request('category')->id == Request('calendar')->category_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'available' => ['required', 'integer', 'max:10']
        ];
    }
}
