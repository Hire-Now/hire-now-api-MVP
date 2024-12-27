<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\Commands\User\FetchUserInformationCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use App\Domain\Contracts\PasswordHasherInterface;
use Carbon\Carbon;

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
