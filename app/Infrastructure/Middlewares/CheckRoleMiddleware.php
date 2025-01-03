<?php

namespace App\Infrastructure\Middlewares;

use App\Application\Contracts\AuthorizationInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    public function __construct(private AuthorizationInterface $authorizationService)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->attributes->get('user_model');

        if (!$user) {
            return response()->json([
                'error'   => 'User not found',
                'message' => 'The user model is missing.'
            ], Response::HTTP_FORBIDDEN);
        }

        if (!$this->authorizationService->hasRole($user, $role)) {
            return response()->json([
                'error'   => 'Forbidden',
                'message' => "User does not have the required role."
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
