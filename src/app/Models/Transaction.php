<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const INCOME_SIGN = '+';
    const EXPENSE_SIGN = '-';

    protected $fillable = [
        'user_id',
        'amount',
        'wallet_id',
        'status',
        'note',
        'date',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'category_id');
    }
}
