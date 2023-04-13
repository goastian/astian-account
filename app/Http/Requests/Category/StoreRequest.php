<?php

namespace App\Http\Requests\Category;

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
            'name' => ['required', 'max:100', 'unique:categories,name'],
            'price' => ['required', 'integer'],
            'air_conditionar' => ['required','boolean'],
            'tv' => ['required','boolean'],
            'bathroom' => ['required','boolean'],
            'hot_water' => ['required','boolean'],
            'cold_water' => ['required','boolean'],
            'wifi' => ['required','boolean'],
            'fan' => ['required','boolean']
        ];
    }
}
