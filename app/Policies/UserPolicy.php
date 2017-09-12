<?php

namespace App\Policies;

use App\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the authenticated user can view the user.
     *
     * @param  \App\User  $authenticatedUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }

    /**
     * Determine whether the authenticated user can update the user.
     *
     * @param  \App\User  $authenticatedUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }

    /**
     * Determine whether the authenticated user can delete the user.
     *
     * @param  \App\User  $authenticatedUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $authenticatedUser, User $user)
    {
        return ($authenticatedUser->id === $user->id) &&
            $authenticatedUser->token()->client->personal_access_client;
    }
}
