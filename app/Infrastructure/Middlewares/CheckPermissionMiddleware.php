<?php

namespace App\Infrastructure\Middlewares;

use App\Application\Contracts\AuthorizationInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    public function __construct(private AuthorizationInterface $authorizationService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->attributes->get('user_model');
        $formattedPermission = ucwords(str_replace('_', ' ', $permission), ' ');

        if (!$user) {
            return response()->json([
                'error'   => 'User not found',
                'message' => 'The user model is missing.'
            ], Response::HTTP_FORBIDDEN);
        }

        if (!$this->authorizationService->hasPermission($user, $formattedPermission)) {
            return response()->json([
                'error'   => 'Forbidden',
                'message' => "User does not have the required permission"
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
