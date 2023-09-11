<?php

namespace App\Http\Requests\Category\Calendar;

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
        return request()->user()->granted();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'available' => [Rule::requiredIf(function () {
                return count(request('category')->rooms()->get()) < 1;
            }),
                'integer',
                'max:10'],
            'add_days' => ['required', 'integer', 'max:365'],
        ];
    }
}
