<?php

namespace App\Application\Commands\Role;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Traits\AccessorTrait;

class ListRolesCommand
{
    use AccessorTrait;

    public function __construct(
        #[Getter] #[Setter]
        private ?string $name,
        #[Getter] #[Setter]
        private ?string $status,
        #[Getter] #[Setter]
        private string $orderBy,
        #[Getter] #[Setter]
        private string $orderDirection
    ) {
    }
}
