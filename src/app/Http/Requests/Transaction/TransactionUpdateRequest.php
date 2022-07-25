<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|integer',
            'wallet_id' => 'required|integer|exists:wallets,id',
            'status' => 'required|in:'.Transaction::INCOME_SIGN.','.Transaction::EXPENSE_SIGN,
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }
}
