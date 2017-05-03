<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const VERIFIED     = '1';
    const NOT_VERIFIED = '0';
    const IS_ADMIN     = 'true';
    const NOT_ADMIN    = 'false';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token', 'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token',
    ];

    public function isVerified()
    {
        return $this->verified == User::VERIFIED;
    }

    public function isAdmin()
    {
        return $this->admin == User::IS_ADMIN;
    }

    public static function generateVerificationToken()
    {
        return str_random(40);
    }
}
