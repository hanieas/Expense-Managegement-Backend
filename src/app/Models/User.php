<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'currency_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Appends new attributes
     *
     * @var array
     */
    protected $append = [
        'signup_path'
    ];


    /**
     * getSignupPathAttribute
     *
     * @return string
     */
    public function getSignupPathAttribute()
    {
        return env('PREFIX_URL') . '/user/signup';
    }

    /**
     * getLoginPathAttribute
     *
     * @return string
     */
    public function getLoginPathAttribute()
    {
        return env('PREFIX_URL') . '/user/login';
    }

    /**
     * getLogoutPathAttribute
     *
     * @return string
     */
    public function getLogoutPathAttribute()
    {
        return env('PREFIX_URL') . '/user/logout';
    }

    /**
     * The user should has a currency.
     *
     * @return void
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    
    /**
     * The user has a list of wallets.
     *
     * @return void
     */
    public function wallets()
    {
        return $this->hasMany(Wallet::class,'user_id');
    }
    
    /**
     * The user has a list of categories.
     *
     * @return void
     */
    public function categories()
    {
        return $this->hasMany(Category::class,'user_id');
    }
}
