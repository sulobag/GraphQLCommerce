<?php

namespace App\GraphQL\Queries;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductSearch
{
    /**
     *
     * @param  array<string,mixed>  $args
     * @return array<string,mixed>
     */
    public function search(null $_, array $args): array
    {
        $term = $args['term'];
        $page = $args['page'] ?? 1;
        $first = $args['first'] ?? 20;

        $builder = Product::search($term)
            ->where('is_active', true);

        if (isset($args['brand'])) {
            $builder->where('brand', $args['brand']);
        }

        if (isset($args['minPrice'])) {
            $builder->where('price', '>=', $args['minPrice']);
        }

        if (isset($args['maxPrice'])) {
            $builder->where('price', '<=', $args['maxPrice']);
        }

        if (! empty($args['inStockOnly'])) {
            // Delegate to an indexed flag; for demo keep it simple.
            $builder->where('inventory.available_quantity', '>', 0);
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $builder->paginate($first, 'page', $page);

        return [
            'items' => $paginator->items(),
            'total' => $paginator->total(),
            'page' => $paginator->currentPage(),
            'first' => $paginator->perPage(),
        ];
    }
}


