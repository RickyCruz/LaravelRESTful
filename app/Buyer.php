<?php

namespace App;

use App\User;
use App\Transaction;
use App\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
