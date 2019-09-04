<?php

namespace App;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use NullableFields;

    protected $table = 'wallet';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    // disable created_at and updated_at
    public $timestamps = false;

    protected $attributes = [
        'balance' => '0.00',
        'currency' => 'CHF'
    ];

    protected $nullable = [
        'description'
    ];

    protected $guarded = ['balance', 'currency'];

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($wallet) {
                $wallet->{$wallet->getKeyName()} = \App\Utils::uuidStr();
            }
        );
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_uuid', 'uuid');
    }
}
