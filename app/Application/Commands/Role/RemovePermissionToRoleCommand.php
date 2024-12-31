<?php

namespace App\Application\Commands\Role;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class RemovePermissionToRoleCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $roleId,
        /** @var uuid[] */
        #[Getter] #[Setter]
        private ?array $permissions
    ) {
    }
}
