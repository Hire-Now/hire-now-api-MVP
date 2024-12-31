<?php

namespace App\Application\Commands\User;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Entities\Role;
use App\Domain\Traits\AccessorTrait;

class SetRoleToUserCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private string $userId,
        /** @var Role[] */
        #[Getter] #[Setter]
        private array $role,
    ) {
    }
}
