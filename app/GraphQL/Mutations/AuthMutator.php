<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AuthMutator
{
    public function register(null $_, array $args): array
    {
        $input = $args['input'];

        // Email kontrolü
        if (User::where('email', $input['email'])->exists()) {
            throw new \Exception('Bu email adresi zaten kayıtlı. Lütfen farklı bir email adresi kullanın.');
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'password' => Hash::make($input['password']),
        ]);

        $tokenResult = $user->createToken('graphql');

        return [
            'user' => $user,
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => $tokenResult->token->expires_at,
        ];
    }

    public function login(null $_, array $args, GraphQLContext $context, ResolveInfo $info): array
    {
        $credentials = Arr::only($args, ['email', 'password']);

        if (! Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $tokenResult = $user->createToken('graphql');

        return [
            'user' => $user,
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => $tokenResult->token->expires_at,
        ];
    }
}


