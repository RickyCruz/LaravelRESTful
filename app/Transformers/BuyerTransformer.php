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
            'name' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'isVerified' => (boolean)($buyer->verified === 1),
            'created' => (string)$buyer->created_at,
            'updated' => (string)$buyer->updated_at,
            'deleted' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
        ];
    }
}
