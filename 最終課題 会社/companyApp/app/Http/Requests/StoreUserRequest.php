<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'company'=> ['required','string', 'max:30'],
            'company_ruby'=> ['required','string', 'max:30'],
            'address'=> ['required','string','max:100'],
            'phone_number'=> ['required','string','max:14'],
            'ceo'=> ['required','string', 'max:30'],
            'ceo_ruby'=> ['required','string', 'max:30'],
        ];
    }
}
