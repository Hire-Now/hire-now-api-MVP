<?php

namespace App\Application\Commands\User;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Entities\User;
use App\Domain\Traits\AccessorTrait;

class GenerateJWTUserCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private User $entity,
    ) {
    }
}
