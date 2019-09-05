<?php

namespace App;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

/**
    The eloquent definition of a wallet -- a container with a chunk of change.

    A wallet is owned by an {@link \App\User}.
 */
class Wallet extends Model
{
    use NullableFields;

    /**
        Our table name for the shall be 'wallet'.

        @var string
     */
    protected $table = 'wallet';

    /**
        The primary key for a wallet is a proper string.

        @see \App\Utils::uuidStr()

        @var string
     */
    protected $primaryKey = 'uuid';
    /**
        {@inheritDoc}
     */
    public $incrementing = false;
    /**
        {@inheritDoc}
     */
    protected $keyType = 'string';

    /**
        {@inheritDoc}
     */
    public $timestamps = false;

    /**
        {@inheritDoc}
     */
    protected $attributes = [
        'balance' => '0.00',
        'currency' => 'CHF'
    ];

    /**
        {@inheritDoc}
     */
    protected $fillable = [
        'currency'
    ];

    /**
        {@inheritDoc}
     */
    protected $nullable = [
        'description'
    ];

    /**
        {@inheritDoc}
     */
    protected $casts = [
        'balance' => 'float',
    ];

    /**
        {@inheritDoc}
     */
    protected $guarded = ['balance'];

    /**
        Provide a custom ID (uuid) property.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($wallet) {
                $wallet->{$wallet->getKeyName()} = \App\Utils::uuidStr();
            }
        );
    }

    public function addController($user)
    {
        if (!$this->controllers()->get()->contains($user)) {
            return $this->controllers()->save($user);
        }
    }

    public function controllers()
    {
        return $this->belongsToMany(
            'App\User',         // The foreign object definition
            'user_accounts',    // The table name
            'wallet_uuid',        // The local foreign key
            'user_uuid'       // The remote foreign key
        );
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_uuid', 'uuid');
    }
}
