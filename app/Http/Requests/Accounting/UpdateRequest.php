<?php

namespace App\Http\Requests\Accounting;

use App\Enum\EnumType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->canWrite() || request()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => ['nullable', 'max:150'],
            'price' => ['nullable', 'decimal:0,2'],
            'type' => ['nullable', Rule::in(EnumType::payment_type())],
            'method' => ['nullable', Rule::in(EnumType::payment_method())],
        ];
    }
}
