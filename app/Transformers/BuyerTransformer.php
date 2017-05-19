<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' => (int)$buyer->id,
            'name'       => (string)$buyer->name,
            'email'      => (string)$buyer->email,
            'isVerified' => (int)$buyer->verified,
            'created'    => (string)$buyer->created_at,
            'updated'    => (string)$buyer->updated_at,
            'deleted'    => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'identifier' => 'id',
            'name'       => 'name',
            'email'      => 'email',
            'isVerified' => 'verified',
            'created'    => 'created_at',
            'updated'    => 'updated_at',
            'deleted'    => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
