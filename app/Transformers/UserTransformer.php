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
            'name'       => (string)$user->name,
            'email'      => (string)$user->email,
            'isVerified' => (int)$user->verified,
            'isAdmin'    => (boolean)($user->admin === 'true'),
            'created'    => (string)$user->created_at,
            'updated'    => (string)$user->updated_at,
            'deleted'    => isset($user->deleted_at) ? (string) $user->deleted_at : null,

            // HATEOAS
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id),
                ],
            ]
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'identifier' => 'id',
            'name'       => 'name',
            'email'      => 'email',
            'pwd'        => 'password',
            'pwd_match'  => 'password_confirmation',
            'isVerified' => 'verified',
            'isAdmin'    => 'admin',
            'created'    => 'created_at',
            'updated'    => 'updated_at',
            'deleted'    => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttributes($index)
    {
        $attributes = [
            'id'                     => 'identifier',
            'name'                   => 'name',
            'email'                  => 'email',
            'password'               => 'pwd',
            'password_confirmation'  => 'pwd_match',
            'verified'               => 'isVerified',
            'admin'                  => 'isAdmin',
            'created_at'             => 'created',
            'updated_at'             => 'updated',
            'deleted_at'             => 'deleted',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
