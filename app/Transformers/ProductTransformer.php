<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier'   => (int)$product->id,
            'title'        => (string)$product->name,
            'details'      => (string)$product->description,
            'stock'        => (int)$product->quantity,
            'availability' => (string)$product->status,
            'image'        => (string)url("img/{$product->image}"),
            'seller'       => (int)$product->seller_id,
            'created'      => (string)$product->created_at,
            'updated'      => (string)$product->updated_at,
            'deleted'      => isset($product->deleted_at) ? (string) $product->deleted_at : null,

            // HATEOAS
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel' => 'product.categories',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id),
                ],
            ]
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'identifier'   => 'id',
            'title'        => 'name',
            'details'      => 'description',
            'stock'        => 'quantity',
            'availability' => 'status',
            'image'        => 'image',
            'seller'       => 'seller_id',
            'created'      => 'created_at',
            'updated'      => 'updated_at',
            'deleted'      => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttributes($index)
    {
        $attributes = [
            'id'          => 'identifier',
            'name'        => 'title',
            'description' => 'details',
            'quantity'    => 'stock',
            'status'      => 'availability',
            'image'       => 'image',
            'seller_id'   => 'seller',
            'created_at'  => 'created',
            'updated_at'  => 'updated',
            'deleted_at'  => 'deleted',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
