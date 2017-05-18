<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'isVerified' => (boolean)($user->verified == 1),
            'isAdmin' => (boolean)($user->admin === 'true'),
            'created' => (string)$user->created_at,
            'updated' => (string)$user->updated_at,
            'deleted' => isset($user->deleted_at) ? (string) $user->deleted_at : null,
        ];
    }
}
