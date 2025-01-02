<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\AssignRoleToUserCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;

class AssignRoleToUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(AssignRoleToUserCommand $command): User
    {
        return $this->userUseCase->setRolesToUser($command->getUserId(), $command->getRoles());
    }
}
