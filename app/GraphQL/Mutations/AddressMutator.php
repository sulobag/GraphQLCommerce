<?php

namespace App\GraphQL\Mutations;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AddressMutator
{
    public function upsert(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Address
    {
        /** @var \App\Models\User $user */
        $user = $context->user();
        $input = $args['input'];

        $address = isset($input['id'])
            ? Address::where('user_id', $user->id)->findOrFail($input['id'])
            : new Address(['user_id' => $user->id]);

        $address->fill([
            'label' => $input['label'],
            'contact_name' => $input['contactName'],
            'line1' => $input['line1'],
            'line2' => $input['line2'] ?? null,
            'city' => $input['city'],
            'state' => $input['state'] ?? null,
            'postal_code' => $input['postalCode'] ?? null,
            'country' => $input['country'],
            'phone' => $input['phone'] ?? null,
            'type' => $input['type'] ?? 'shipping',
            'is_primary' => $input['isPrimary'] ?? false,
            'metadata' => $input['metadata'] ?? null,
        ]);

        $address->user()->associate($user);
        $address->save();

        return $address;
    }

    public function delete(null $_, array $args, GraphQLContext $context): bool
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $address = Address::where('user_id', $user->id)->findOrFail($args['id']);

        return (bool) $address->delete();
    }
}


