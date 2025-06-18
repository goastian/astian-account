<?php

namespace App\Http\Requests\UserGroup;

use App\Models\Subscription\Group;
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
