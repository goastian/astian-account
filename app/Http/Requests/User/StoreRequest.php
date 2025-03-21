<?php
namespace App\Http\Requests\User;

use App\Models\User\User;
use Illuminate\Validation\Rule;
use App\Models\Subscription\Group;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:100'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'country' => ['required', 'max:150'],
            'city' => ['nullable', 'regex:/^[A-Za-z\s]+$/', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'dial_code' => [Rule::requiredIf(request()->phone != null), 'max:8', 'exists:countries,dial_code'],
            'phone' => [Rule::requiredIf(request()->dial_code != null), 'max:25', 'unique:users,phone'],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
            'verify_email' => ['nullable', 'boolean'],
            'groups' => [
                'required',
                function ($attribute, $value, $fail) {

                    if (empty($value)) {
                        $fail(__('The groups field is required.'));
                    }

                    if (!is_array($value)) {
                        $fail(__('The groups field must be an array.'));
                    }

                    foreach ($value as $key) {

                        if (is_array($key)) {
                            $fail(__('The group field has a wrong value.'));
                            break;
                        }

                        $group = Group::find($key);

                        if (empty($group)) {
                            $fail(__('The group with ID :key does not exist.', ['key' => $key]));
                        }
                    }
                }
            ],
        ];
    }
}
