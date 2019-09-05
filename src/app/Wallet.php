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
        'balance' => 0.00,
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

        @todo migrate to observers

        @return void
     */
    protected static function boot()
    {
        parent::boot();

        // retrieved, creating, created, updating, updated, saving, saved, deleting, deleted,
        // restoring, restored
        static::creating(
            function ($wallet) {
                $wallet->{$wallet->getKeyName()} = \App\Utils::uuidStr();
            }
        );

        // Prevent a wallet with a positive of negative balance from being deleted.
        static::deleting(
            function ($wallet) {
                if ($wallet->balance != 0.00) {
                    return false;
                }

                if ($wallet->owner->wallets()->count() <= 1) {
                    return false;
                }
            }
        );
    }

    /**
        Add a controller to this wallet.
     */
    public function addController($user)
    {
        if (!$this->controllers()->get()->contains($user)) {
            return $this->controllers()->save($user);
        }
    }

    /**
        Remove a controller from this wallet.

        @return void
     */
    public function removeController($user)
    {
        if ($this->controllers()->get()->contains($user)) {
            return $this->controllers()->forget($user);
        }
    }

    /**
        Add an amount of pecunia to this wallet's balance.

        @param float $amount The amount of pecunia to add.

        @return Wallet
     */
    public function credit(float $amount)
    {
        $this->balance += $amount;

        return $this;
    }

    /**
        Deduct an amount of pecunia from this wallet's balance.

        @param float $amount The amount of pecunia to deduct.

        @return Wallet
     */
    public function debit(float $amount)
    {
        $this->balance -= $amount;

        return $this;
    }

    /**
        Controllers of this wallet.

        @return User[]
     */
    public function controllers()
    {
        return $this->belongsToMany(
            'App\User',         // The foreign object definition
            'user_accounts',    // The table name
            'wallet_uuid',        // The local foreign key
            'user_uuid'       // The remote foreign key
        );
    }

    /**
        Entitlements billed to this wallet.

        @return Entitlement[]
     */
    public function entitlements()
    {
        return $this->hasMany('App\Entitlement', 'wallet_uuid', 'uuid');
    }

    /**
        The owner of the wallet -- the wallet is in his/her back pocket.

        @return User
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_uuid', 'uuid');
    }
}
