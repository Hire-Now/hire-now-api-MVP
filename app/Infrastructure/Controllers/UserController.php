<?php

namespace App\Infrastructure\Controllers;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\Handlers\User\CreateUserCommandHandler;
use App\Application\UseCases\UserUseCase;
use App\Domain\Enums\Roles;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use App\Infrastructure\Requests\CreateUserRequest;
use App\Infrastructure\Services\BcryptPasswordHasher;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserController
{
    public function store(CreateUserRequest $request)
    {
        try {
            $request = $request->validated();

            $command = new CreateUserCommand(
                $request['name'],
                $request['email'],
                $request['password'],
                $request['birth_date'],
                Roles::CANDIDATE //todo: determinar de que manera identificar el rol
            );

            $handler = new CreateUserCommandHandler(new UserUseCase(new UserRepository, new BcryptPasswordHasher()));

            $user = $handler->handle($command);

            return response()->json([
                'message' => 'User created successfully!',
                'user'    => $user
            ]);

        } catch (BadRequestException $th) {
            return response()->json([
                'message'   => $th->getMessage(),
                'user' => []
            ], status: 400);
        } catch (\Throwable $th) {
            return response()->json([
                'message'   => 'An unexpected error just happened!',
                'user' => []
            ], 500);
        }
    }
}
