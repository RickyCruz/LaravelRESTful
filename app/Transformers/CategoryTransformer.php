<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'title'      => (string)$category->name,
            'details'    => (string)$category->description,
            'created'    => (string)$category->created_at,
            'updated'    => (string)$category->updated_at,
            'deleted'    => isset($category->deleted_at) ? (string) $category->deleted_at : null,
        ];
    }

    public static function originalAttributes($index)
    {
        $attributes = [
            'identifier' => 'id',
            'title'      => 'name',
            'details'    => 'description',
            'created'    => 'created_at',
            'updated'    => 'updated_at',
            'deleted'    => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
