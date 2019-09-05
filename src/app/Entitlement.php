<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
    The eloquent definition of an entitlement.

    Owned by a {@link \App\User}, billed to a {@link \App\Wallet}.
 */
class Entitlement extends Model
{
    protected $table = 'entitlement';

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['uuid'];

    protected $fillable = ['sku_uuid', 'user_uuid', 'wallet_uuid', 'description'];

    /**
        Provide a custom ID (uuid) property.

        @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($entitlement) {
                $entitlement->{$entitlement->getKeyName()} = \App\Utils::uuidStr();
            }
        );
    }

    public function sku()
    {
        return $this->belongsTo('App\Sku', 'sku_uuid', 'uuid');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_uuid', 'uuid');
    }

    public function wallet()
    {
        return $this->belongsTo('App\Wallet', 'wallet_uuid', 'uuid');
    }
}
