<?php

namespace App\Traits;

trait AdminActions
{
    /**
     * Register conditions to run before all Gate checks.
     *
     * @param  $user
     * @param  $ability
     * @return boolean
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }
}
