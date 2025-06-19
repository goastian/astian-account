<?php

namespace App\Http\Requests\Group;

use App\Rules\BooleanRule;
use App\Models\Subscription\Group;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use Asset;
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
                'max:100',
                function ($attribute, $value, $fail) {
                    $slug = $this->slug($value);

                    $checkSlug = Group::where('slug', $slug)->first();
                    if ($checkSlug) {
                        $fail(__("The :attribute already exists", ['attribute' => $attribute]));
                    }
                }
            ],
            'description' => ['required', 'max:190'],
            'system' => ['required', 'boolean'],
        ];
    }
}
