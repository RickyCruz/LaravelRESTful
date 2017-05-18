<?php

namespace App;

use App\User;
use App\Transaction;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    /**
     * Using Fractal to transfrom model.
     * @var class
     */
    public $transformer = BuyerTransformer::class;

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
