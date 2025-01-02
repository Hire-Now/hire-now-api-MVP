<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use Carbon\Carbon;

class DeleteUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(string $userId): bool
    {
        return $this->userUseCase->deleteUser($userId);
    }
}
