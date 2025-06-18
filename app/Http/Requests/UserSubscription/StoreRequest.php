<?php

namespace App\Http\Requests\UserSubscription;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plan' => ['required', 'exists:plans,id'],
            'billing_period' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_null(billing_get_period($value))) {
                        $fail(__("The :attribute is not valid", ['attribute' => $attribute]));
                    }
                }
            ],
            'payment_method' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_null(billing_get_method($value))) {
                        $fail(__("The :attribute is not valid", ['attribute' => $attribute]));
                    }
                }
            ],
        ];
    }
}
