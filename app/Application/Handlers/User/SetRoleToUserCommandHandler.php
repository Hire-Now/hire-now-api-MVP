<?php

namespace App\Application\Handlers\User;

use Carbon\Carbon;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use App\Application\UseCases\UserUseCase;
use App\Application\Commands\User\SetRoleToUserCommand;
use App\Domain\Entities\Role;

class SetRoleToUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(SetRoleToUserCommand $command): void
    {
        $this->userUseCase->setRoleToUser($command->getUserId(), $command->getRole());
    }
}
