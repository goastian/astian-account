<?php

namespace App\Http\Requests\Category;

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
            'name' => ['nullable', 'max:100', 'unique:categories,name,' . Request('category')->id],
            'price' => ['nullable', 'integer'],
            'capacity' => ['nullable', 'integer', 'max:20'],
            'air_conditionar' => ['nullable', Rule::in(EnumType::yes_or_not())],
            'tv' => ['nullable', Rule::in(EnumType::yes_or_not())],
            'bathroom' => ['nullable', Rule::in(EnumType::yes_or_not())],
            'hot_water' => ['nullable', Rule::in(EnumType::yes_or_not())],
            'cold_water' => ['nullable', Rule::in(EnumType::yes_or_not())],
            'wifi' => ['nullable', Rule::in(EnumType::yes_or_not())],
            'fan' => ['nullable', Rule::in(EnumType::yes_or_not())],
        ];
    }
}
