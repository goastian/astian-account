<?php

namespace App\Http\Requests\BookingRentClient;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user_validate() and $this->exists_huesped();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function exists_huesped()
    {
        return request('room')->huespeds()->get()->contains(request('huesped')->id);
    }

    public function user_validate()
    {
        return request()->user()->granted() and (request('booking')->id == request('room')->booking_id);
    }
}
