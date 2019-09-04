<?php

namespace App;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use NullableFields;

    // do not use the plural for table names
    protected $table = 'user';

    // avoid using a column 'id' with basic auto-increment logic.
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'bigint';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'login', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
        The attributes that can be null.
     */
    protected $nullable = [
        'name',
        'login',
        'remember_token',
        'email_verified_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($user) {
                $user->{$user->getKeyName()} = \App\Utils::uuidInt();
            }
        );

        static::created(
            function ($user) {
                $user->wallets()->create();
            }
        );
    }

    public function wallets()
    {
        return $this->hasMany('App\Wallet', 'user_uuid', 'uuid');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
}
