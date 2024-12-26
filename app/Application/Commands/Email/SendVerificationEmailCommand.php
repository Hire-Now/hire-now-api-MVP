<?php

namespace App\Application\Commands\Email;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Entities\EmailVerification;
use App\Domain\Traits\AccessorTrait;

class SendVerificationEmailCommand
{
    use AccessorTrait;

    public function __construct(#[Getter] #[Setter] private ?EmailVerification $emailVerification)
    {
    }
}
