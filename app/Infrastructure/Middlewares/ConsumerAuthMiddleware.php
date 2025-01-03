<?php

namespace App\Infrastructure\Middlewares;

use App\Application\Contracts\ConsumerAuthInterface;
use App\Infrastructure\Persistence\Eloquent\Models\ApiConsumer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsumerAuthMiddleware
{
    public function __construct(private ConsumerAuthInterface $authService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $authorization = $request->header('Consumer-Authorization');

            if (!$authorization) {
                return response()->json([
                    'status'  => 'ERROR',
                    'message' => 'Consumer authorization was not provided.',
                    'data'    => []
                ], 401);
            }

            $consumer = $this->authService->authenticate($authorization);

            if (!$consumer) {
                return response()->json([ 'error' => 'Unauthorized' ], 401);
            }

            $request->attributes->set('api_consumer', $consumer);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Provided consumer auth is invalid',
                'data'    => []
            ], 401);
        }
    }
}
