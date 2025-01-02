<?php

namespace App\Application\Commands\User;

use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;
use App\Domain\Entities\Role;
use App\Domain\Traits\AccessorTrait;

class ListUsersCommand
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
