<?php

namespace App\Http\Requests\UserSubscription;

use Illuminate\Foundation\Http\FormRequest;

class RenewRequest extends FormRequest
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
            'package' => ['required', 'exists:packages,id'],
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
