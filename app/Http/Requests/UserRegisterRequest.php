<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as Password_rule;
class UserRegisterRequest extends FormRequest
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
            'name' => 'required|max:55|string',
            'email' => 'email|required|unique:users',
            'password' => ['required', 'confirmed',
                Password_rule::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()],
            'company_id' => 'required|exists:companies,id',
            'role' => 'required|string',
            'language_preference' => 'in:en,ar'
        ];
        
    }
    public function messages()
    {

        return [
            'name.required' => 'name filed is required',
            'name.max' => 'name should be less than 55 charecters',
            'name.string' => 'name should be string',
            'email.required' => 'email filed is required',
            'email.digits' => 'Phone should be 12 number',
            'password.required' => 'password filed is required',
        ];

    }
}
