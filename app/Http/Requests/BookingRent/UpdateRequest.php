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
        return $this->can_access() && $this->belongs_to();;
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
            'price' => ['nullable', 'integer'],
        ];
    }

    /**
     * comprueba que tenga los permisos necesarios
     */
    public function can_access()
    {
        return request()->user()->canWrite() || request()->user()->isAdmin();
    }

    /**
     * verifica que el regristro en rent pertenesca al booking  
     */
    public function belongs_to()
    {
        return Request('booking')->id == Request('rent')->booking_id;
    }
}
