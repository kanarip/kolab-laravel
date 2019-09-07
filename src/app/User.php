<?php

namespace Kolab;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
    The eloquent definition of an authenticatable user.
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use NullableFields;

    // do not use the plural for table names
    public $incrementing = false;
    protected $keyType = 'bigint';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // A humanly legible name, almost like a display name (i.e. "John Doe")
        'name',
        // A login (i.e. username), because logging in with the email address advertised for
        // external communications is just... well...
        'login',
        // The actual email address.
        'email',
        'locale',
        'country',
        'currency',
        'timezone',
        // The account password.
        'password',
    ];

    protected $attributes = [
        'locale' => 'en',
        'country' => 'CH',
        'timezone' => 'Europe/Zurich',
        'currency' => 'CHF'
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

    /**
        Provide a custom ID (integer uuid) and ensure a wallet is attached.

        @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($user) {
                $user->{$user->getKeyName()} = \Kolab\Utils::uuidInt();
                $user->password = bcrypt(Str::random(64));
            }
        );

        static::created(
            function ($user) {
                $user->wallets()->create();
            }
        );
    }

    /**
        Any wallets on which this user is a controller.

        @return Wallet[]
     */
    public function accounts()
    {
        return $this->belongsToMany(
            'Kolab\Wallet',       // The foreign object definition
            'user_accounts',    // The table name
            'user_id',        // The local foreign key
            'wallet_id'       // The remote foreign key
        );
    }

    /**
        Entitlements for this user.

        @return Entitlement[]
     */
    public function entitlements()
    {
        return $this->hasMany('Kolab\Entitlement');
    }

    public function addEntitlement($entitlement)
    {
        if (!$this->entitlements()->get()->contains($entitlement)) {
            return $this->entitlements()->save($entitlement);
        }
    }

    /**
        Wallets this user owns.

        @return Wallet[]
     */
    public function wallets()
    {
        return $this->hasMany('Kolab\Wallet');
    }

    /**
        The identifier used in JWT tokens.

        @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
        Custom claims.

        @todo what is this?

        @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
