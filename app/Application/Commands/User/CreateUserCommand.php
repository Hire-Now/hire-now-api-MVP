<?php

namespace App\Application\Commands\User;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Enums\ElementStatus;
use App\Domain\Enums\Roles;
use App\Domain\Traits\AccessorTrait;
use Carbon\Carbon;

class CreateUserCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $name,
        #[Getter] #[Setter]
        private string $email,
        #[Setter] #[Getter]
        private string $password,
        #[Setter] #[Getter]
        private Carbon $birthDate,
        #[Setter] #[Getter]
        private Roles $role,
    ) {
    }
}
