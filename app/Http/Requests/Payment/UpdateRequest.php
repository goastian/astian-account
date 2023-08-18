<?php

namespace App\Http\Requests\Payment;
 
use App\Enum\EnumType; 
use Illuminate\Validation\Rule; 
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
        return request()->user()->granted() and request('booking')->id == request('payment')->paymentable_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => ['nullable', 'max:150'],
            'price' => ['nullable', 'decimal:0,2'],
            'type' => ['nullable', Rule::in(EnumType::payment_type())],
            'method' => ['nullable', Rule::in(EnumType::payment_method())],
        ];
    }
}
