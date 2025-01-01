<?php

namespace App\Application\Handlers\User;

use App\Application\Commands\User\CheckUserCredentialsCommand;
use App\Application\UseCases\UserUseCase;
use App\Domain\Entities\User;
use App\Domain\Enums\ElementStatus;
use Carbon\Carbon;

class CheckUserCredentialsCommandHandler
{
    public function __construct(private UserUseCase $userUseCase)
    {
    }

    public function handle(CheckUserCredentialsCommand $command): User
    {
        return $this->userUseCase->validateCredentials($command->getEmail(), $command->getPassword());
    }
}
