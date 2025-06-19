<?php

namespace App\Http\Requests\Plan;

use App\Rules\BooleanRule;
use App\Models\Subscription\Plan;
use App\Repositories\Traits\Generic;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    use Generic, Asset;

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
            'name' => [
                'required',
                'max:150',
                function ($attribute, $value, $fail) {
                    $plan = Plan::where('slug', $this->slug($value))->first();

                    if (!empty($plan)) {
                        $fail(__("This plan has been registered"));
                    }

                }
            ],
            'description' => ['required'],
            'active' => ['required', 'boolean'],
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
            'prices' => [
                'required',
                'array'
            ],
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
