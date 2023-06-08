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
            'company_id'=>['required'],
            'name'=>['required','string', 'max:30'],
            'name_ruby'=>['required','string', 'max:30'],
            'address'=>['required','string', 'max:30'],
            'phone_number'=> ['required','string','max:14'],
            'department'=>['required','string', 'max:30'],
            'to'=>['required','string', 'max:30'],
            'to_ruby'=>['required','string', 'max:30'],
        ];
    }
}
