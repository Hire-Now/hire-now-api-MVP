<?php

namespace App\Application\Commands\User;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Enums\ElementStatus;
use App\Domain\Enums\Roles;
use App\Domain\Traits\AccessorTrait;
use Carbon\Carbon;

class UpdateUserInformationCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $id,
        #[Getter] #[Setter]
        private ?string $name,
        #[Getter] #[Setter]
        private ?string $email,
        #[Getter] #[Setter]
        private ?string $password,
        #[Setter] #[Getter]
        private ?Carbon $birthDate,
        #[Setter] #[Getter]
        private ?Carbon $lastActivity,
        #[Setter] #[Getter]
        private ?ElementStatus $status,
        #[Setter] #[Getter]
        private bool $userActivation = false
    ) {
    }
}
