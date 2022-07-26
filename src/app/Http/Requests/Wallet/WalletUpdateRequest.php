<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WalletUpdateRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('wallets')->where(function ($query) {
                    return $query->where('name', '=', request()->name)
                        ->where('user_id', '=', Auth::user()->id)
                        ->whereNull('deleted_at');
                })->ignore($this->wallet->id)
            ],
            'inventory' => 'required|integer',
        ];
    }

    /**
     * bodyParameters
     *
     * @return array
     */
    public function bodyParameters()
    {
        return [
            'name' => [
                'description' => 'The name of the wallet.',
                'example' => 'Mellat'
            ],
            'inventory' => [
                'description' => 'The inventory of the Wallet.',
                'example' => 10000,
            ],
        ];
    }
}
