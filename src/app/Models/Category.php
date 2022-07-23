<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    /**
     * @var array
     */
    protected $fillable =[
        'user_id',
        'name',
    ];
    
    /**
     * Get the user that category belongs to.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
