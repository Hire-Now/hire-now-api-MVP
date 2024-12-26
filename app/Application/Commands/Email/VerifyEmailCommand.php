<?php

namespace App\Application\Commands\Email;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Enums\Roles;
use App\Domain\Traits\AccessorTrait;
use Carbon\Carbon;

class VerifyEmailCommand
{
    use AccessorTrait;

    public function __construct(#[Getter] #[Setter] private ?string $verifyToken)
    {}
}
