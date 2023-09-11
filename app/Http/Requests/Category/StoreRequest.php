<?php

namespace App\Http\Requests\Category;

use App\Enum\EnumType;
use Illuminate\Validation\Rule;
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
            'name' => ['required', 'max:100', 'unique:categories,name'],
            'price' => ['required', 'integer'],
            'capacity' => ['required', 'integer', 'max:20'],
            'air_conditionar' => ['required', Rule::in(EnumType::yes_or_not())],
            'tv' => ['required', Rule::in(EnumType::yes_or_not())],
            'bathroom' => ['required', Rule::in(EnumType::yes_or_not())],
            'hot_water' => ['required', Rule::in(EnumType::yes_or_not())],
            'cold_water' => ['required', Rule::in(EnumType::yes_or_not())],
            'wifi' => ['required', Rule::in(EnumType::yes_or_not())],
            'fan' => ['required', Rule::in(EnumType::yes_or_not())]
        ];
    }
}
