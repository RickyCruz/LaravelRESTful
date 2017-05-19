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
            'name'       => (string)$seller->name,
            'email'      => (string)$seller->email,
            'isVerified' => (int)$seller->verified,
            'created'    => (string)$seller->created_at,
            'updated'    => (string)$seller->updated_at,
            'deleted'    => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
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
