<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return request()->booking->is_reservation() ?
        $this->reservation_rules() :
        $this->booking_rules();
    }

    public function reservation_rules()
    {
        return [
            'check_in' => ['nullable', 'date_format:Y-m-d H:i', 'after_or_equal:' . date('Y-m-d', strtotime(now()))],
            'check_out' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:' . date('Y-m-d', strtotime($this->get_check_in() . ' + 1 days'))],
            'ruc' => ['nullable'],
        ];

    }

    public function booking_rules()
    {
        return [
            'check_out' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:' . date('Y-m-d')],
            'ruc' => ['nullable'],
        ];
    }

    /**
     * obtiene el check_in
     */
    public function get_check_in()
    {
        return request()->check_in ? request()->check_in : request()->booking->check_in;
    }

    /**
     * obtiene el check_ot
     */
    public function get_check_out()
    {
        return request()->check_out ? request()->check_out : request()->booking->check_out;
    }
}
