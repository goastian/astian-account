<?php
namespace App\Http\Requests\Subscription;

use App\Models\User\Scope;
use App\Models\User\UserSubscription;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * User subscription instance
     * @var 
     */
    public $subscription;

    public function __construct()
    {
        parent::__construct();
        $this->subscription = new UserSubscription();
    }
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
            'user_id' => ['required', 'exists:users,id'],
            'target_type' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->subscription->checkTarget($value)) {
                        $fail(__("The :attribute is not available for registration.", ['attribute' => $attribute]));
                    }
                }
            ],
            'target_id' => [
                'required',
                function ($attribute, $value, $fail) {

                    $scope = new Scope();

                    if ($scope->type == 'scope') {

                        if (request()->target_type != $scope->type) {
                            $fail(__("The :attribute is not valid", ['attribute' => $attribute]));
                        }

                        $scope = $scope->find($scope);
                        if (!$scope) {
                            $fail(__("The :attribute is not valid", ['attribute' => $attribute]));
                        }
                    }
                }
            ],
            'price_plan' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && is_numeric($value)) {
                        $fail(__("The :attribute must be a number.", ['attribute' => $attribute]));
                    }
                }
            ],
            'price_scope' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && is_numeric($value)) {
                        $fail(__("The :attribute must be a number.", ['attribute' => $attribute]));
                    }
                }
            ],
            'start_date' => ['nullable'],
            'end_date' => ['nullable'],
            'trial_start_at' => ['nullable'],
            'trial_duration_days' => ['nullable'],
            'cancellation_date' => ['nullable'],
            'last_renewal_at' => ['nullable'],
            'next_payment_due' => ['nullable'],
            'is_recurring' => ['nullable'],
            'status' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->subscription->checkStatus($value)) {
                        $fail(__("The status is not available"));
                    }
                }
            ],
            'system' => ['nullable', 'boolean']
        ];
    }
}
