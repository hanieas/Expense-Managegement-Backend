<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WalletStoreRequest extends FormRequest
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
            'user_id' => 'integer|required|exists:users,id',
            'name' => [
                'required',
                Rule::unique('wallets')->where(function ($query) {
                    return $query->where('name', '=', request()->name)
                        ->where('user_id', '=', Auth::user()->id);
                })
            ],
            'inventory' => 'integer',
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
            'user_id' => [
                'description' => 'The ID of owner user, No need to fill this field. This will extract automatically from token'
            ],
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
