<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\Consumer\AuthenticateConsumerCommand;
use App\Application\Handlers\Consumer\AuthenticateConsumerCommandHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class ConsumerController
{
    public function __construct(private AuthenticateConsumerCommandHandler $authenticateConsumerCommandHandler)
    {
    }

    public function authenticate(Request $request)
    {
        try {
            $authorization = $request->header('Authorization');

            if (!$authorization) {
                throw new UnauthorizedException('Provided authorization was invalid.');
            }

            $authenticateConsumerCommand = new AuthenticateConsumerCommand($authorization);

            $jwt = $this->authenticateConsumerCommandHandler->handle($authenticateConsumerCommand);

            return response()->json([
                'status'  => 'SUCCESS',
                'message' => 'Authentication successful',
                'data'    => [
                    'token'      => $jwt,
                    'type'       => 'Bearer',
                    'expires_in' => config('app.user_auth.jwt_validity_time') . ' ' . config('app.user_auth.jwt_type_time')
                ]
            ], 200);
        } catch (UnauthorizedException $th) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => $th->getMessage(),
                'data'    => []
            ], 500);
        } catch (\Throwable $th) {
            logger()->error("Error in UserController@authenticate: {$th->getMessage()}", [
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'status'  => 'ERROR',
                'message' => 'Failed to authenticate the consumer, please try again later.',
                'data'    => []
            ], 500);
        }
    }
}
