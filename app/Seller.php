<?php

namespace App;

use App\User;
use App\Product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    /**
     * Using Fractal to transfrom model.
     * @var class
     */
    public $transformer = SellerTransformer::class;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
