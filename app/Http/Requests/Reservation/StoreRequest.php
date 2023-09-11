<?php

namespace App\Http\Requests\Reservation;

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
        return request()->user()->canWrite() || request()->user()->isAdmin();;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            //datos del cliente
            'name' => ['required', 'string', 'max:90'],
            'last_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required','max:9'],
            'country' => ['required', 'string', ':max:100'],
            'city' => ['required', 'string', ':max:100'],

           //datos del la instancia
            'check_in' => ['required', 'date_format:Y-m-d H:i', 'after_or_equal:' . date('Y-m-d')],
            'check_out' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . date('Y-m-d', strtotime(now() . ' + 1 days'))],

            //datos de la habitación
            'rooms' => ['required', 'array'], 
            'rooms.*.category_id' => ['required','exists:categories,id'],
            'rooms.*.price' => ['required','integer'],

            //datos de la empresa
            'company' => ['nullable','max:150'],
            'ruc' => ['nullable', 'max:20']
        ];
    }
}
