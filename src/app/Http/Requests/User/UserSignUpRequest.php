<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserSignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|unique:users|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required|min:6',
            'currency_id' => 'required|integer|exists:currencies,id',
            'username' => 'required|string|min:6'
        ];
    }
}
