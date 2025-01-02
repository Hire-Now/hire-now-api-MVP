<?php

namespace App\Application\Handlers\User;

use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;

class GetUserCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(string $userId): User
    {
        return $this->userUseCase->findUserById(new User(
            $userId,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
        ));
    }
}
