<?php

namespace App\Http\Requests\BookingRentClient;

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
        return request()->user()->granted() and (request('booking')->id == request('room')->booking_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:100'],
            'last_name' => ['required', 'max:100'],
            'document' => ['required', 'max:100'],
            'number' => ['required', 'max:50'],
            'city' => ['required', 'max:100'],
            'country' => ['required', 'max:100'],
            'email' => ['nullable','email', 'max:100'],
            'phone' => ['required', 'max:9']
        ];
    }
}
