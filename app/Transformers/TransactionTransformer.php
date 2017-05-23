<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int)$transaction->id,
            'quantity'   => (int)$transaction->quantity,
            'buyer'      => (int)$transaction->buyer_id,
            'product'    => (int)$transaction->product_id,
            'created'    => (string)$transaction->created_at,
            'updated'    => (string)$transaction->updated_at,
            'deleted'    => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,

            // HATEOAS
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $transaction->id),
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index', $transaction->id),
                ],
                [
                    'rel' => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $transaction->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $transaction->buyer_id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $transaction->product_id),
                ],
            ]
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'identifier' => 'id',
            'quantity'   => 'quantity',
            'buyer'      => 'buyer_id',
            'product'    => 'product_id',
            'created'    => 'created_at',
            'updated'    => 'updated_at',
            'deleted'    => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttributes($index)
    {
        $attributes = [
            'id'         => 'identifier',
            'quantity'   => 'quantity',
            'buyer_id'   => 'buyer',
            'product_id' => 'product',
            'created_at' => 'created',
            'updated_at' => 'updated',
            'deleted_at' => 'deleted',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
