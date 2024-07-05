<?php

namespace App\Http\Middleware;

use App\Models\User\UserEntity;
use App\Repositories\UsersRepository;
use App\ValueObjects\TokenValueObject;
use Closure;

class CheckTokenExpiry
{
    public function __construct(
        private UsersRepository $usersRepository,
    )
    {
    }

    public function handle($request, Closure $next)
    {
        //why condition does not work?
//      $user = $this->usersRepository->findByToken($request->bearerToken()->first());

        $user = UserEntity::where('token', $request->bearerToken()->first())->firstOrFail();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        $token = new TokenValueObject($user->login_token, $user->login_token_expires_at);

        if ($token->isExpired()) {
            return response()->json([
                'message' => 'Token expired',
            ], 401);
        }

        return $next($request);
    }

}
