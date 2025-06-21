<?php

namespace App\Http\Requests\Service;

use App\Rules\StringOnlyRule;
use Illuminate\Validation\Rule;
use App\Models\Subscription\Service;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => ['nullable', new StringOnlyRule()],
            'description' => ['nullable', 'max:190'],
            'visibility' => ['nullable', Rule::in(Service::visibilities())]
        ];
    }
}
