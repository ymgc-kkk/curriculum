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
            'company'=> ['string', 'max:30'],
            'company_ruby'=> ['string', 'max:30'],
            'address'=> ['string','max:100'],
            'phone_number'=> ['string','max:14'],
            'ceo'=> ['string', 'max:30'],
            'ceo_ruby'=> ['string', 'max:30'],
            'billing'=>['string', 'max:30'],
            'billing_ruby'=>['string', 'max:30'],
            'department'=>['string', 'max:30'],
            'to'=>['string', 'max:30'],
            'to_ruby'=>['string', 'max:30'],
        ];
    }
}
