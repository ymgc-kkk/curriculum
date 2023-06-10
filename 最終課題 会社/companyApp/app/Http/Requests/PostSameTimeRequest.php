<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostSameTimeRequest extends FormRequest
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
            'name'=> ['string', 'max:30'],
            'name_ruby'=> ['string', 'max:30'],
            'address'=> ['string','max:100'],
            'phone_number'=> ['string','max:14'],
            'ceo'=> ['string', 'max:30'],
            'ceo_ruby'=> ['string', 'max:30'],
            'address.name'=> ['string', 'max:30'],
            'address.name_ruby'=> ['string', 'max:30'],
            'address.address'=> ['string', 'max:100'],
            'address.phone_number'=> ['string', 'max:14'],
            'address.department'=> ['string', 'max:30'],
            'address.to'=> ['string', 'max:30'],
            'address.to_ruby'=> ['string', 'max:30'],
        ];
    }
}
