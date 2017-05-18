<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identifier' => (int)$seller->id,
            'name' => (string)$seller->name,
            'email' => (string)$seller->email,
            'isVerified' => (boolean)($seller->verified === 1),
            'created' => (string)$seller->created_at,
            'updated' => (string)$seller->updated_at,
            'deleted' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
        ];
    }
}
