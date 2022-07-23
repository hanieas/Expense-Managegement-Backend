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
        'name',
        'user_id',
        'inventory',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];
    
    /**
     * @var array
     */
    protected $with=[
        'user',
    ];

    /**
     * Each wallet belongs to a user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
