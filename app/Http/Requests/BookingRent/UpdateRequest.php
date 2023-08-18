<?php

namespace App\Http\Requests\BookingRent;

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
        return (request()->user()->canWrite() || request()->user()->isAdmin()) && Request('booking')->id == Request('room')->booking_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'room_id' => ['nullable', 'exists:rooms,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['nullable', 'integer']
        ];
    }
}
