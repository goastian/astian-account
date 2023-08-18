<?php

namespace App\Http\Requests\Booking;

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
            // datos del registro
            'check_out' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . date('Y-m-d')],

            // datos de la habitación
            'rooms' => ['required', 'array'],
            'rooms.*.id' => ['distinct:ignore_case', 'required','exists:rooms,id'],
            'rooms.*.category_id' => ['required','exists:categories,id'],
            'rooms.*.price' => ['required','integer'],
 
            // datos de la empresa
            //'company' => ['nullable'],
            //'ruc' => ['nullable'],
        ];
    }
}
