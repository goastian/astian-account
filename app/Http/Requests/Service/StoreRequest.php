<?php

namespace App\Http\Requests\Service;

use App\Rules\BooleanRule;
use App\Rules\StringOnlyRule;
use Illuminate\Validation\Rule;
use App\Models\Subscription\Service;
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
            'name' => ['required', new StringOnlyRule(),],
            'description' => ['required', 'max:190'],
            'group_id' => ['required', 'exists:groups,id'],
            'system' => ['nullable', 'boolean'],
            'visibility' => ['required', Rule::in(Service::visibilities())]
        ];
    }
}
