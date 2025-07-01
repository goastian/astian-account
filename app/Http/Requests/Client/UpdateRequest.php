<?php

namespace App\Http\Requests\Client;

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
            'name' => 'nullable|max:191',
            'redirect' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $urls = explode(',', $value);
                        foreach ($urls as $url) {
                            $url = trim($url);
                            if (!preg_match('/^(https?:\/\/)/i', $url)) {
                                $fail(__(
                                    'Each URL in :attribute must start with http or https. invalid url :value',
                                    ['attribute' => $attribute, 'value' => $url]
                                ));
                                break;
                            }
                        }
                    }
                }
            ],
        ];
    }
}
