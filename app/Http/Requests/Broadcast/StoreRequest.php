<?php

namespace App\Http\Requests\Broadcast;

use App\Rules\BooleanRule;
use App\Rules\StringOnlyRule;
use App\Models\Broadcasting\Broadcast;
use Elyerr\ApiResponse\Assets\Asset;
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
            'name' => [
                'required',
                new StringOnlyRule(),
                'max:100',
                function ($attribute, $value, $fail) {

                    $model = Broadcast::where('slug', $value)->first();

                    if ($model) {
                        $fail(__(
                            "The :attribute has been registered",
                            ['attribute' => $attribute]
                        ));
                    }
                }
            ],
            'description' => ['required', 'max:350'],
            'system' => ['nullable', 'boolean'],
        ];
    }
}
