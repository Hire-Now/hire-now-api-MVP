<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use App\Domain\Contracts\PasswordHasherInterface;
use Carbon\Carbon;

class CreateUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(CreateUserCommand $command): User
    {
        return $this->userUseCase->createUser(new User(
            null,
            $command->getName(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getBirthDate(),
            $command->getRole(),
            ElementStatus::INACTIVE,
            null,
            Carbon::now(),
        ));
    }
}
