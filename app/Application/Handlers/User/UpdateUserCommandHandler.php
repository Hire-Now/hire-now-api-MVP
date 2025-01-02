<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\Commands\User\FetchUserInformationCommand;
use App\Application\Commands\User\UpdateUserInformationCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use App\Domain\Contracts\PasswordHasherInterface;
use Carbon\Carbon;

class UpdateUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(UpdateUserInformationCommand $command): User
    {
        return $this->userUseCase->updateUser(new User(
            $command->getId(),
            $command->getName(),
            $command->getEmail(),
            null,
            $command->getBirthDate(),
            null,
            $command->getStatus(),
            null,
            $command->getLastActivity()
        ));
    }
}
