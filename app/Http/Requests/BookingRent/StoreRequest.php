<?php

namespace App\Http\Requests\BookingRent;

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
            'room_id' => ['required', 'exists:rooms,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer']
        ];
    }
}
