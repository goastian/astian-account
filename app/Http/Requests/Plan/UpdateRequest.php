<?php

namespace App\Http\Requests\Plan;

use App\Repositories\Traits\Generic;
use App\Rules\BooleanRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    use Generic;

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
            'name' => ['nullable', 'max:150'],
            'description' => [
                function ($attribute, $value, $fail) {
                    if (!strip_tags($value)) {
                        $fail(__('The :attribute field is required'));
                    }
                }
            ],
            'active' => ['nullable', 'boolean'],
            'trial_enabled' => ['nullable', 'boolean'],
            'trial_duration' => [
                'required_if:trial_enabled,true',
                'integer',
                'min:0',
                'max:255',
            ],
            'bonus_enabled' => ['nullable', 'boolean'],
            'bonus_duration' => [
                'required_if:bonus_enabled,true',
                'integer',
                'min:0',
                'max:255',
            ],
            'scopes' => [
                'required',
                'array',
                'exists:scopes,id',
                function ($attribute, $value, $fail) {

                    $duplicated = $this->checkServices($value);

                    if (count($duplicated) > 0) {
                        $fail(__("The following services (:services) contain duplicate roles", ['services' => implode(', ', $duplicated)]));
                    }
                }
            ],
            'prices' => ['nullable', 'array'],
            'prices.*.billing_period' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {

                    $billingPeriods = array_column(request()->prices, 'billing_period');

                    if (count($billingPeriods) !== count(array_unique($billingPeriods))) {
                        $fail("Billing periods must be unique.");
                    }

                    if (empty(billing_get_period($value))) {
                        $fail("The billing period is invalid");
                    }
                }
            ],
            'prices.*.currency' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (empty(billing_get_currency($value))) {
                        $fail("The billing period is invalid");
                    }
                }
            ],
            'prices.*.amount' => ['required', 'integer', 'min:0'],
        ];
    }
}
