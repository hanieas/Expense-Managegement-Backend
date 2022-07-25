<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
        'inventory',
    ];

    protected $casts = [
        'inventory' => 'integer',
    ];
    
    /**
     * getPathAttribute
     *
     * @return string
     */
    public function getPathAttribute(): string
    {
        return env('PREFIX_URL') . '/wallets';
    }


    /**
     * Each wallet belongs to a user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * The user has a list of transactions.
     *
     * @return object
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'wallet_id');
    }
}
