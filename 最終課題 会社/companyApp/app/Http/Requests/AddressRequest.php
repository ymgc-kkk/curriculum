<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'name'=>['required','string', 'max:50'],
            'name_ruby'=>['required','string', 'max:50'],
            'address'=>['required','string', 'max:100'],
            'phone_number'=> ['required','string','max:14'],
            'department'=>['required','string', 'max:50'],
            'to'=>['required','string', 'max:50'],
            'to_ruby'=>['required','string', 'max:50'],
        ];
    }
}
