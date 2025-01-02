<?php

namespace App\Application\Commands\User;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class RemoveRoleToUserCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $userId,
        /** @var uuid[] */
        #[Getter] #[Setter]
        private ?array $roles
    ) {
    }
}
