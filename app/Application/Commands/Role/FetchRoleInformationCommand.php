<?php

namespace App\Application\Commands\Role;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class FetchRoleInformationCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        /** @var string[] */
        private ?array $roles,
    ) {
    }
}
