<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\ValidEmail;
use App\Rules\ValidPhone;

class OtpRequest extends FormRequest
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
            'provider' => [
                'required',
                'string',
                Rule::in(['email', 'phone'])
            ],
            'value' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $provider = $this->input('provider');

                    if ($provider === 'email') {
                        $emailRule = new ValidEmail();
                        $emailRule->validate($attribute, $value, $fail);
                    } elseif ($provider === 'phone') {
                        $phoneRule = new ValidPhone();
                        $phoneRule->validate($attribute, $value, $fail);
                    }
                }
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'provider.required' => 'The provider field is required.',
            'provider.in' => 'The provider must be either email or phone.',
            'value.required' => 'The value field is required.',
            'value.string' => 'The value must be a string.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'provider' => 'authentication provider',
            'value' => 'email or phone number',
        ];
    }
}
