<?php

namespace App\Application\Commands\Email;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Enums\Roles;
use App\Domain\Traits\AccessorTrait;
use Carbon\Carbon;

class CreateEmailVerificationCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $userId,
        #[Getter] #[Setter]
        private ?string $email,
    ) {}
}
