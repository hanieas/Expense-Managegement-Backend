<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'inventory',
    ];
    
    /**
     * Wallet belongs to a user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
