<?php

namespace App\Infrastructure\Middlewares;

use App\Infrastructure\Persistence\Eloquent\Models\ApiConsumer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsumerAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {//todo: refactor this
        $authorization = $request->header('Authorization');

        if (!$authorization || !str_starts_with($authorization, 'Basic ')) {
            return response()->json([ 'error' => 'Unauthorized' ], 401);
        }

        $decoded = base64_decode(substr($authorization, 6));
        [ $clientId, $clientSecret ] = explode(':', $decoded, 2);

        $consumer = ApiConsumer::where('client_id', $clientId)->first();

        if (!$consumer || !password_verify($clientSecret, $consumer->client_secret) || !$consumer->is_active) {
            return response()->json([ 'error' => 'Unauthorized' ], 401);
        }

        $consumer->update([ 'last_access_at' => now() ]);

        $request->attributes->set('api_consumer', $consumer);

        return $next($request);
    }
}
