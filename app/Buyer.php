<?php

namespace App;

use App\User;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{

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
