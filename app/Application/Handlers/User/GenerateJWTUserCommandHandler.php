<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\GenerateJWTUserCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;


class GenerateJWTUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(GenerateJWTUserCommand $command): string
    {
        return $this->userUseCase->generateJWT($command->getEntity());
    }
}
