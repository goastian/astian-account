<?php

namespace App\Http\Requests\UserScope;

use App\Models\Subscription\Scope;
use Illuminate\Database\QueryException;
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
            'scopes' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        $fail(__("The :attribute must be an array", ['attribute' => $attribute]));
                    }

                    if (is_array($value)) {
                        foreach ($value as $id) {
                            try {
                                $scopes = Scope::find($id);
                                if (!$scopes) {
                                    $fail(__("The :attribute is not valid", ["attribute" => $attribute, "id" => $id]));
                                }
                            } catch (QueryException) {
                                $fail(__(
                                    "The :attribute is not valid",
                                    ["attribute" => $attribute, "id" => $id]
                                ));
                            }
                        }
                    }
                }
            ],
            'end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value && strtotime(now() > strtotime($value))) {
                        $fail(__(
                            "The :attribute must be a date greater than the current date.",
                            ['attribute' => $attribute]
                        ));
                    }
                }
            ]
        ];
    }
}
