<?php
namespace App\Http\Requests\User;

use App\Models\User\User;
use App\Rules\BooleanRule;
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
            'name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . Request('user')->id],
            'country' => ['nullable', 'max:100', 'exists:countries,name_en'],
            'dial_code' => [Rule::requiredIf(request()->phone != null), 'max:8', 'exists:countries,dial_code'],
            'phone' => [Rule::requiredIf(request()->dial_code != null), 'max:25', 'unique:users,phone,' . Request('user')->id],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
            'verify_email' => ['nullable', 'boolean'],
            'commission_rate' => ['nullable', 'numeric']
        ];
    }
}
