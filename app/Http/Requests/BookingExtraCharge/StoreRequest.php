<?php

namespace App\Http\Requests\BookingExtraCharge;

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
            'charge' => ['required', 'max:150'],
            'amount' => ['required', 'integer'],
            'price' => ['required', 'decimal:0,2']
        ];
    }
}
