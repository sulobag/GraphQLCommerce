<?php

namespace App\GraphQL\Mutations;

use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductMutator
{
    public function upsert(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Product
    {
        $input = $args['input'];

        $product = isset($input['id'])
            ? Product::findOrFail($input['id'])
            : new Product();

        $product->fill([
            'sku' => $input['sku'],
            'title' => $input['title'],
            'brand' => $input['brand'] ?? null,
            'category' => $input['category'] ?? 'default',
            'description' => $input['description'] ?? null,
            'price' => $input['price'],
            'currency' => $input['currency'] ?? 'TRY',
            'is_active' => $input['isActive'] ?? true,
            'primary_image_url' => $input['primaryImageUrl'] ?? null,
            'search_tags' => $input['searchTags'] ?? null,
            'metadata' => $input['metadata'] ?? null,
        ]);

        $product->save();

        return $product;
    }
}


