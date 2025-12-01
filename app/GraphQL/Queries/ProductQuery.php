<?php

namespace App\GraphQL\Queries;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductQuery
{
    /**
     * @param  array<string,mixed>  $args
     * @return array<string,mixed>
     */
    public function active(null $_, array $args, GraphQLContext $context, ResolveInfo $info): array
    {
        $page = $args['page'] ?? 1;
        $first = $args['first'] ?? 20;

        /** @var LengthAwarePaginator $paginator */
        $paginator = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate($first, ['*'], 'page', $page);

        return [
            'data' => $paginator->items(),
            'paginatorInfo' => [
                'count' => $paginator->count(),
                'currentPage' => $paginator->currentPage(),
                'firstItem' => $paginator->firstItem(),
                'hasMorePages' => $paginator->hasMorePages(),
                'lastItem' => $paginator->lastItem(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ];
    }
}


