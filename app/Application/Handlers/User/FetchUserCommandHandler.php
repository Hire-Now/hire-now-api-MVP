<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\FetchUserInformationCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;

class FetchUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(FetchUserInformationCommand $command): User
    {
        return $this->userUseCase->findUserById(new User(
            $command->getId(),
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        ));
    }
}
