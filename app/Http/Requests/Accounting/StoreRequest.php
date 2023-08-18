<?php

namespace App\Http\Requests\Accounting;

use App\Enum\EnumType;
use Illuminate\Validation\Rule;
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
            'description' => ['required', 'max:150'],
            'price' => ['required', 'decimal:0,2'],
            'type' => ['required', Rule::in(EnumType::payment_type())],
            'method' => ['required', Rule::in(EnumType::payment_method())],
        ];
    }
}
