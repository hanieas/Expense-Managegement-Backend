<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const INCOME_SIGN = '+';
    const EXPENSE_SIGN = '-';
    
    /**
     * The attributes that are fillable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'wallet_id',
        'status',
        'note',
        'date',
        'category_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'wallet_id' => 'integer',
        'category_id' => 'integer',
    ];
    
    /**
     * getPathAttribute
     *
     * @return string
     */
    public function getPathAttribute(): string
    {
        return env('PREFIX_URL') . '/transactions';
    }
    
    /**
     * Each transaction belongs to a user.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Each transaction belongs to a wallet.
     *
     * @return void
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }
    
    /**
     * Each transaction belongs to a category.
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
