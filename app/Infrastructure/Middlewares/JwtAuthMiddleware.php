<?php

namespace App\Infrastructure\Middlewares;

use App\Domain\Contracts\JWTServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    public function __construct(private readonly JWTServiceInterface $jwtService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {//todo: Ajustar para usar basic para generar JWT
        try {
            $authorization = $request->header('User-Authorization');

            if (!$authorization) {
                return response()->json([
                    'status'  => 'ERROR',
                    'message' => 'User authorization was not provided.',
                    'data'    => []
                ], 401);
            }

            $attributes = $this->jwtService->validateToken($authorization);

            $request->attributes->add([ 'user_entity' => $attributes['entity'], 'user_model' => $attributes['model'] ]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Provided token is invalid',
                'data'    => []
            ], 401);
        }
    }
}
