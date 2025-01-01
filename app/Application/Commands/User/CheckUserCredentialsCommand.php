<?php

namespace App\Application\Commands\User;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class CheckUserCredentialsCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $email,
        #[Setter] #[Getter]
        private string $password,
    ) {
    }
}
